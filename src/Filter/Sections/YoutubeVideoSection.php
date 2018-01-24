<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter\Sections;

class YoutubeVideoSection {

    public function parse($input, $parameters){

        return [
            "type" => "oembed",
            "data" => [
                "type" => "youtube",
                "url" => "https://youtu.be/".$input["id"],
                "html" => $input["embed"]
            ]
        ];
    }

}