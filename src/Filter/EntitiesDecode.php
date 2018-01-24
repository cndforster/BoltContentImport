<?php

namespace Topolis\Bolt\Extension\ContentImport\Filter;

use Exception;
use Silex\Application;
use Topolis\Bolt\Extension\ContentImport\IFilter;
use Topolis\FunctionLibrary\Collection;

class EntitiesDecode implements IFilter {

    public static function filter($input, $parameters, Application $app, $values, $source){
        return html_entity_decode($input);
    }

}