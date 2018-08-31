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
    public function __construct($params)
    {   $this->user=$params["user"];
        $this->pwd=$params["pwd"];
        $this->serviceFactory=new ServiceFactory();
        $this->serviceFactory->registerService("Viessman","Viessman\Oauth\ViessmanOauthService");
        $this->storage=new Session();
        $this->credentials = new Credentials("79742319e39245de5f91d15ff4cac2a8","8ad97aceb92c5892e102b093c7c083fa","vicare://oauth-callback/everest");
        $this->viessmanOauthService=$this->serviceFactory->createService('Viessman', $this->credentials,$this->storage, $this->scope,new Uri('https://api.viessmann-platform.io'));
    }

     function getToken($code){
            return $this->viessmanOauthService->requestAccessToken($code);

    }
    public function getCode():string
    {
        $client_id = '79742319e39245de5f91d15ff4cac2a8';
        $client_secret = '8ad97aceb92c5892e102b093c7c083fa';

        $isiwebuserid = '';   //to be modified
        $isiwebpasswd = '';          //to be modified

        $authorizeURL = 'https://iam.viessmann.com/idp/v1/authorize';
        $callback_uri = "vicare://oauth-callback/everest";
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

}