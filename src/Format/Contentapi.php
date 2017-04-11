<?php

namespace Topolis\Bolt\Extension\ContentImport\Format;

use Exception;
use Topolis\Bolt\Extension\ContentImport\IFormat;

class Contentapi extends BaseFormat implements IFormat {

    public function parse($url){

        $input = $this->getUrl($url);

        $array = json_decode($input, true);

        $result = [
            "channel" => [],
            "items" => $array["items"]
        ];

        return $result;
    }
}