<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter\Sections;

class TextSection {

    public function parse($input){
        return [
            "type" => "text",
            "data" => [
                "text" => $input["text"],
                "format" => "html"
            ]
        ];
    }

}