<?php

namespace Topolis\Bolt\Extension\ContentImport\Format;

use Exception;

class BaseFormat {

    protected $config;

    public function __construct(array $config){
        $this->config = $config;
    }

    /**
     * @param $url
     * @return mixed
     * @throws Exception
     */
    protected function getUrl($url){
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', $url);

        if($res->getStatusCode() != 200)
            throw new Exception("Error ".$res->getStatusCode()." while requesting source '".$url);

        return (string)$res->getBody();
    }

}