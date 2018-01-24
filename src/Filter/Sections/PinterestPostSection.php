<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter\Sections;

class PinterestPostSection {

    public function parse($input, $parameters){

        // Pinterest is not directly supported by OEmbed2 -> Use custom Text
        return [
            "type" => "pinterest",
            "data" => [
                "text" => "https://www.pinterest.com/pin/".$input["id"]."/",
                "format" => "html",
                "custom_type" => "Pinterest"
            ]
        ];
    }

}