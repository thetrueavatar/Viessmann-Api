<?php
/**
 * Created by PhpStorm.
 * User: clibois
 * Date: 31/08/18
 * Time: 12:27
 */

namespace Viessman\Oauth;


use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;

class ViessmanOauthClient
{
    private $viessmanOauthService;
    private $storage;
    private $credentials;
    private $serviceFactory;
    private $scope=["openid"];
    private $user;
    private $pwd;
    /**
     * ViessmanOauthClient constructor.
     * @param $viessmanOauthService
     */
    const CONSUMERID = "79742319e39245de5f91d15ff4cac2a8";

    const CONSUMERSECRET = "8ad97aceb92c5892e102b093c7c083fa";

    const HTTPS_IAM_VIESSMANN_COM_IDP_V_1_AUTHORIZE = 'https://iam.viessmann.com/idp/v1/authorize';

    const VICARE_OAUTH_CALLBACK_EVEREST = "vicare://oauth-callback/everest";

    public function __construct($params)
    {   $this->user=$params["user"];
        $this->pwd=$params["pwd"];
        $this->serviceFactory=new ServiceFactory();
        $this->serviceFactory->registerService("Viessman","Viessman\Oauth\ViessmanOauthService");
        $this->storage=new Session();
        $this->credentials = new Credentials("" . self::CONSUMERID, "" . self::CONSUMERSECRET, self::VICARE_OAUTH_CALLBACK_EVEREST);
        $this->viessmanOauthService=$this->serviceFactory->createService('Viessman', $this->credentials,$this->storage, $this->scope,new Uri('https://api.viessmann-platform.io'));
    }

     function getToken($code){
            return $this->viessmanOauthService->requestAccessToken($code);

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
        preg_match_all($pattern, $response, $matches);
        return ($matches[1][0]);
    }

    public function request($resourceUrl):string
    {
        return $this->viessmanOauthService->request($resourceUrl);
    }

}