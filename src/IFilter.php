<?php

namespace Topolis\Bolt\Extension\ContentImport;

use Silex\Application;

interface IFilter {

    /**
     * @param $input
     * @param $parameters
     * @param Application $app
     * @param $values
     * @return array
     */
    public static function filter($input, $parameters, Application $app, $values);

}
