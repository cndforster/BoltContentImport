<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter\Sections;

class AsideSection {

    public function parse($input, $parameters){

        $asideType = isset($parameters["aside-purpose-map"][$input["purpose"]]) ? $parameters["aside-purpose-map"][$input["purpose"]] : "info";

        return [
            "type" => $asideType,
            "data" => [
                "text" => $input["text"],
                "format" => "html",
                "custom_type" => $asideType
            ]
        ];
    }

}