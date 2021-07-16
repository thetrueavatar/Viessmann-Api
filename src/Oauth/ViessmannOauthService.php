<?php

namespace Viessmann\Oauth;

use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth2\Service\AbstractService;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * Bootstrap the example
 */
final class ViessmannOauthService extends AbstractService
{

    const SCOPE_USAGE_GET = 'IoT%20User';
    private $authorizeURL = 'https://iam.viessmann.com/idp/v2/authorize';
    private $token_url = 'https://iam.viessmann.com/idp/v2/token';
    protected $redirect_uri = "http://localhost:4200/";

    /**
     * ViessmannOauthClient constructor.
     */
    public function __construct(
        CredentialsInterface $credentials,
        ClientInterface $httpClient,
        TokenStorageInterface $storage,
        $scopes = array(),
        UriInterface $baseApiUri = null
    )
    {
        parent::__construct(
            $credentials,
            $httpClient,
            $storage,
            $scopes,
            $baseApiUri,
            true
        );
        $this->clientId=$credentials->getConsumerId();
    }
    public function setCodeChallenge($codeChallenge){
        $this->codeChallenge=$codeChallenge;
    }
    public function getAuthorizationEndpoint()
    {
        return new Uri($this->authorizeURL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint()
    {
        return new Uri($this->token_url);
    }

    protected function getAuthorizationMethod()
    {
        return static::AUTHORIZATION_METHOD_HEADER_BEARER;
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = (array)json_decode($responseBody);
        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException(
                'Error in retrieving token: "' . $data['error'] . '"'
            );
        }
        $token = new StdOAuth2Token();
        $token->setAccessToken($data['access_token']);
        $token->setLifetime($data['expires_in']);
        if (isset($data['refresh_token'])) {
            $token->setRefreshToken($data['refresh_token']);
            unset($data['refresh_token']);
        }
        unset($data['access_token']);
        unset($data['expires_in']);
        $token->setExtraParams($data);
        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUri(array $additionalParameters = array())
    {
        $parameters = array_merge(
            $additionalParameters,
            array(
                'type' => 'web_server',
                'client_id' => $this->clientId,
                'redirect_uri' => $this->credentials->getCallbackUrl(),
                'response_type' => 'code',
                'code_challenge'=>$this->codeChallenge,
                'scope' => ViessmannOauthService::SCOPE_USAGE_GET
            )
        );
        // special, hubic use a param scope with commas
        // between scopes instead of spaces
        $parameters['scope'] = "openid";
        if ($this->needsStateParameterInAuthUrl()) {
            if (!isset($parameters['state'])) {
                $parameters['state'] = $this->generateAuthorizationState();
            }
            $this->storeAuthorizationState($parameters['state']);
        }
        // Build the url
        $url = clone $this->getAuthorizationEndpoint();
        foreach ($parameters as $key => $val) {
            $url->addToQuery($key, $val);
        }
        return $url;
    }

    public function request($path, $method = 'GET', $body = null, array $extraHeaders = array())
    {
        return parent::request($path, $method, $body, $extraHeaders);
    }

    public function requestAccessToken($code, $state = null)
    {
        if (null !== $state) {
            $this->validateAuthorizationState($state);
        }

        $bodyParams = array(
            'code'          => $code,
            'client_id'     => $this->credentials->getConsumerId(),
            'redirect_uri'  => $this->credentials->getCallbackUrl(),
            'code_verifier' => $this->codeChallenge,
            'grant_type'    => 'authorization_code',
        );

        $responseBody = $this->httpClient->retrieveResponse(
            $this->getAccessTokenEndpoint(),
            $bodyParams,
            $this->getExtraOAuthHeaders()
        );

        $token = $this->parseAccessTokenResponse($responseBody);
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }



}