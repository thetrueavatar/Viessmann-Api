<?php declare(strict_types=1);

namespace Viessman\API;

use TomPHP\Siren\{ActionBuilder, Entity, Action};
final class ViessmanAPI
{

    private $isiwebuserid = '';   //to be modified
    private $isiwebpasswd = '';          //to be modified
    private $access_token;
    private $code;
    private $client_id = '79742319e39245de5f91d15ff4cac2a8';
    private $client_secret = '8ad97aceb92c5892e102b093c7c083fa';
    private $authorizeURL = 'https://iam.viessmann.com/idp/v1/authorize';
    private $token_url = 'https://iam.viessmann.com/idp/v1/token';
    private $apiURLBase = 'https://api.viessmann-platform.io';
    private $general = '/general-management/installations?expanded=true&';
    private $redirect_uri = "vicare://oauth-callback/everest";

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->client_id;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    /**
     * @return string
     */
    public function getAuthorizeURL(): string
    {
        return $this->authorizeURL;
    }

    /**
     * @return string
     */
    public function getTokenUrl(): string
    {
        return $this->token_url;
    }

    /**
     * @return string
     */
    public function getApiURLBase(): string
    {
        return $this->apiURLBase;
    }

    /**
     * @return string
     */
    public function getGeneral(): string
    {
        return $this->general;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirect_uri;
    }


    /**
     * ViessmanAPI constructor.
     */
    public function __construct()

    {


        $code=$this->getCode();
        echo "code:".$code;

//    $token=$oauth2->get_access_token();
//        $clientConfig=new ClientConfig(false);
//        $clientConfig->canonicalServerUrl("https://iam.viessmann.com/idp/v1/authorize");
//        $clientConfig->
//        $oauthClient=new Client();
//        $code = getCode();
//        $access_token = getAccessToken($code);
//        $resource = getResource($access_token, $apiURLBase . $general);
//        $installation = json_decode($resource, true)["entities"][0]["properties"]["id"];
//        $gw = json_decode($resource, true)["entities"][0]["entities"][0]["properties"]["serial"];
//        $resource = getResource($access_token, "https://api.viessmann-platform.io/operational-data/installations/$installation/gateways/$gw/devices/0/features/" . TEMP_OUTSIDE_FEATURE . "");

    }

    /**
     * @return string
     */
    public function getIsiwebuserid(): string
    {
        return $this->isiwebuserid;
    }

    /**
     * @return string
     */
    public function getIsiwebpasswd(): string
    {
        return $this->isiwebpasswd;
    }


    function getAuthorizationCode() : string
    {
        $url = "https://iam.viessmann.com/idp/v1/authorize?client_id=79742319e39245de5f91d15ff4cac2a8&scope=openid&redirect_uri=vicare://oauth-callback/everest&response_type=code";
        $header = array("Content-Type: application/x-www-form-urlencoded");
        $curloptions = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "",
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

    function getCode(): string
    {
        $url = $this->getAuthorizeURL()."?client_id=".$this->getClientId()."&scope=openid&redirect_uri=".$this->getRedirectUri()."&response_type=code";
        $header = array("Content-Type: application/x-www-form-urlencoded");
        $curloptions = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->getIsiwebuserid().":".$this->getIsiwebpasswd(),
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
    function getAccessToken($authorization_code)
    {
        $header = array("Content-Type: application/x-www-form-urlencoded;charset=utf-8");
        $params = array(
            "client_id" => $this->getClientId(),
            "client_secret" => $this->getClientSecret(),
            "code" => $authorization_code,
            "redirect_uri" => $this->getRedirectUri(),
            "grant_type" => "authorization_code");

        $curloptions = array(
            CURLOPT_URL => $this->getTokenUrl(),
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => rawurldecode(http_build_query($params)));

        $curl = curl_init();
        curl_setopt_array($curl, $curloptions);
        $response = curl_exec($curl);
        curl_getinfo($curl);
        curl_close($curl);

        if ($response === false) {
            echo "Failed\n";
            echo curl_error($curl);

        } elseif (!empty(json_decode($response)->error)) {
            echo "Error:\n";
            echo $authorization_code;
            echo $response;
        }

        return json_decode($response)->access_token;
    }

//    we can now use the access_token as much as we want to access protected resources
    function getResource($access_token, $api)
    {
        echo "ok\n";
        $header = array("Authorization: Bearer {$access_token}");
        var_dump($header);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $model = Entity::fromArray(json_decode($response, true));

        echo 'Model: ' . $model->getProperty('_id') . PHP_EOL;
        //return json_decode($response, true);
        return ($response);
    }

    function debug_msg($message, $debug)
    {
        if ($debug) {
            echo "$message\n";
        }
    }
}
