<?php

namespace Topolis\Bolt\Extension\ContentImport;

use Bolt\Extension\SimpleExtension;
use Pimple as Container;
use Silex\Application;

use Topolis\Bolt\Extension\ContentImport\Command\Import;
use Topolis\Bolt\Extension\ContentImport\Service\Importer;

class Extension extends SimpleExtension {

    const EXTID = "topolis.contentimport";

    /**
     * {@inheritdoc}
     */
    protected function registerNutCommands(Container $container)
    {
        return [
            new Import($container),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $app[self::EXTID.'.importer'] = $app->share(
            function ($app) {
                return new Importer($app, $this->getConfig());
            }
        );
    }
}