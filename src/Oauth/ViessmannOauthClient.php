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

class ViessmannOauthClient
{
    private $viessmannOauthService;
    private $storage;
    private $credentials;
    private $serviceFactory;
    private $scope=["openid"];
    private $user;
    private $pwd;
    /**
     * ViessmannOauthClient constructor.
     * @param $viessmannOauthService
     */
    const CONSUMERID = "79742319e39245de5f91d15ff4cac2a8";

    const CONSUMERSECRET = "8ad97aceb92c5892e102b093c7c083fa";

    const HTTPS_IAM_VIESSMANN_COM_IDP_V_1_AUTHORIZE = 'https://iam.viessmann.com/idp/v1/authorize';

    const VICARE_OAUTH_CALLBACK_EVEREST = "vicare://oauth-callback/everest";

    public function __construct($params)
    {   $this->user=$params["user"];
        $this->pwd=$params["pwd"];
        $this->serviceFactory=new ServiceFactory();
        $httpClient = new CurlClient();
        $this->serviceFactory->setHttpClient($httpClient);
        $this->serviceFactory->registerService("Viessmann","Viessmann\Oauth\ViessmannOauthService");
        $this->storage=new Session();
        $this->credentials = new Credentials("" . self::CONSUMERID, "" . self::CONSUMERSECRET, self::VICARE_OAUTH_CALLBACK_EVEREST);
        $this->viessmannOauthService=$this->serviceFactory->createService('Viessmann', $this->credentials,$this->storage, $this->scope,new Uri('https://api.viessmann-platform.io'));
    }

    function getToken($code)
    {
        return $this->viessmannOauthService->requestAccessToken($code);

    }

    public function getCode():string
    {
        $client_id = self::CONSUMERID;
        $authorizeURL = self::HTTPS_IAM_VIESSMANN_COM_IDP_V_1_AUTHORIZE;
        $callback_uri = self::VICARE_OAUTH_CALLBACK_EVEREST;
        $url = "$authorizeURL?client_id=$client_id&scope=openid&redirect_uri=$callback_uri&response_type=code";
        $header = array("Content-Type: application/x-www-form-urlencoded");
        $curloptions = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
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
            throw new ViessmannApiException("Error during authentication process. Please review your username/password");
        }
    }

    public function readData($resourceUrl):string
    {
        return $this->viessmannOauthService->request($resourceUrl);
    }

    public function setData($resourceUrl, $data)
    {
        $headers=[
            "Content-Type"=>"application/json",
            "Accept"=>"application/vnd.siren+json"
        ];
        return $this->viessmannOauthService->request($resourceUrl,'POST',$data,$headers);
    }

}