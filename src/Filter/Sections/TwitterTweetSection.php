<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter\Sections;

class TwitterTweetSection {

    public function parse($input, $parameters){

        return [
            "type" => "oembed",
            "data" => [
                "type" => "twitter",
                "url" => "https://twitter.com/".$input["account"]."/status/".$input["status"],
                "html" => $input["embed"]
            ]
        ];
    }

}