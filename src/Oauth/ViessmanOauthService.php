<?php declare(strict_types=1);
namespace Viessman\Oauth;

use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth2\Service\AbstractService;
use OAuth\OAuth2\Token\StdOAuth2Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;

/**
 * Bootstrap the example
 */
final class ViessmanOauthService extends AbstractService {

    const SCOPE_USAGE_GET       = 'openid';
    protected $client_id = '79742319e39245de5f91d15ff4cac2a8';
    protected $client_secret = '8ad97aceb92c5892e102b093c7c083fa';
    private $authorizeURL = 'https://iam.viessmann.com/idp/v1/authorize';
    private $token_url = 'https://iam.viessmann.com/idp/v1/token';
    protected $redirect_uri = "vicare://oauth-callback/everest";
    /**
     * ViessmanOauthClient constructor.
     */
    public function __construct(
        CredentialsInterface $credentials,
        ClientInterface $httpClient,
        TokenStorageInterface $storage,
        $scopes = array(),
        UriInterface $baseApiUri = null
    ) {
        parent::__construct(
            $credentials,
            $httpClient,
            $storage,
            $scopes,
            $baseApiUri,
            true
        );
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
    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationMethod()
    {
        return static::AUTHORIZATION_METHOD_HEADER_BEARER;
    }
    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data=(array)json_decode($responseBody);
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
                'type'          => 'web_server',
                'client_id'     => $this->credentials->getConsumerId(),
                'redirect_uri'  => $this->credentials->getCallbackUrl(),
                'response_type' => 'code',
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
}