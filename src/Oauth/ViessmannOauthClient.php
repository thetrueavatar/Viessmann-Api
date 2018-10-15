<?php
namespace Viessmann\Oauth;
interface ViessmannOauthClient
{
    public function getToken($code);

    public function getCode(): string;

    public function readData($resourceUrl): string;

    public function setData($feature, $action, $data);
}