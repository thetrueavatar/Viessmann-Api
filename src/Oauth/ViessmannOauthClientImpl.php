<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 31/08/18
 * Time: 12:27
 */

namespace Viessmann\Oauth;


use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\CurlClient;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Storage\Session;
use OAuth\ServiceFactory;
use Viessmann\API\ViessmannApiException;


class ViessmannOauthClientImpl implements ViessmannOauthClient
{
    private $viessmannOauthService;
    private $storage;
    private $credentials;
    private $serviceFactory;
    private $scope = ["IoT%20User"];
    private $user;
    private $pwd;

    /**
     * ViessmannOauthClient constructor.
     * @param $viessmannOauthService
     */
    const BASE_URL = 'https://api.viessmann.com/iot/v1/';

    const HTTPS_IAM_VIESSMANN_COM_IDP_V_1_AUTHORIZE = 'https://iam.viessmann.com/idp/v2/authorize';

    const REDIRECT_URL = "http://localhost:4200/";

    const CODE_CHALLENGE = "2e21faa1-db2c-4d0b-a10f-575fd372bc8c-575fd372bc8c";

    public function __construct($username,$password,$clientId)
    {   $this->clientId=$clientId;
        $this->user = $username;
        $this->pwd = $password;
        $this->serviceFactory = new ServiceFactory();
        $httpClient = new CurlClient();
        $httpClient->setCurlParameters([CURLOPT_SSL_VERIFYPEER => false]);
        $this->serviceFactory->setHttpClient($httpClient);
        $this->serviceFactory->registerService("Viessmann", "Viessmann\Oauth\ViessmannOauthService");
        $this->storage = new Session();
        $this->credentials = new Credentials("" . $this->clientId, "", self::REDIRECT_URL);
        $this->viessmannOauthService = $this->serviceFactory->createService('Viessmann', $this->credentials, $this->storage, $this->scope, new Uri('' . self::BASE_URL . ''));
        $this->viessmannOauthService->setCodeChallenge(self::CODE_CHALLENGE);
        $code = $this->getCode();
        $this->getToken($code);
    }

    function getToken($code)
    {
        return $this->viessmannOauthService->requestAccessToken($code);

    }

    public function getCode(): string
    {
        $authorizeURL = self::HTTPS_IAM_VIESSMANN_COM_IDP_V_1_AUTHORIZE;
        $callback_uri = self::REDIRECT_URL;
        $url = "$authorizeURL?client_id=$this->clientId&scope=IoT%20User&redirect_uri=$callback_uri&response_type=code&code_challenge=" . self::CODE_CHALLENGE . "";
        $curloptions = array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "$this->user:$this->pwd",
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_POST => true,
        );
        $curl = curl_init();
        curl_setopt_array($curl, $curloptions);
        $response = curl_exec($curl);
        curl_close($curl);
        $matches = array();
        $pattern = '/code=(.*)"/';
        if (preg_match_all($pattern, $response, $matches)) {
            return ($matches[1][0]);
        } else {
            throw new ViessmannApiException("Error during authentication process. Please review your username/password", 0, new ViessmannApiException("response didn't contains code to get token probably due to an error in authentication process. Response : " . $response));
        }
    }

    public function readData($resourceUrl): string
    {
        return $this->viessmannOauthService->request($resourceUrl);
    }

    public function setData($url, $data)
    {
        $headers = [
            "Content-Type" => "application/json",
            "Accept" => "application/vnd.siren+json"
        ];
        return $this->viessmannOauthService->request($url, 'POST', $data, $headers);
    }


}
