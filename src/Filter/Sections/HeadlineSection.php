<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter\Sections;

class HeadlineSection {

    public function parse($input){
        return [
            "type" => "heading",
            "data" => [
                "text" => $input["text"],
                "level" => $input["level"], // Not supported by Sir-Trevor atm
                "format" => "html"
            ]
        ];
    }

}