<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter\Sections;

class InstagramPostSection {

    public function parse($input, $parameters){

        return [
            "type" => "oembed",
            "data" => [
                "type" => "instagram",
                "url" => "https://www.instagram.com/p/".$input["id"],
                "html" => $input["embed"]
            ]
        ];
    }

}