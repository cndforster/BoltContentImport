<?php
namespace Topolis\Bolt\Extension\ContentImport\Service;
use Bolt\Storage\Collection\Taxonomy;
use Bolt\Storage\Entity\Content;
use DateTime;
use Exception;
use Silex\Application;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Topolis\Bolt\Extension\ContentImport\IFilter;
use Topolis\Bolt\Extension\ContentImport\IFormat;
use Topolis\FunctionLibrary\Collection;
use Topolis\FunctionLibrary\Token;

/**
 * An nut command for then KoalaCatcher extension.
 *
 * @author Kenny Koala
 * <kenny@dropbear.com.au>
 */
class Importer {

    /* @var array $config */
    protected $config;
    /* @var Application $app */
    protected $app;

    protected static $defaults = [
        "tasks" => []
    ];

    protected static $defaultField = [
        "source" => "unknown",
        "filters" => [],
        "default" => false
    ];


    public function __construct($app, $config){
        $this->app = $app;
        $this->config = $config + self::$defaults;
    }

    /**
     * @param string|bool $source
     * @param OutputInterface|bool $output
     * @param bool $verbose
     */
    public function import($source = false, $output = false, $verbose = false){

        foreach($this->config["tasks"] as $key => $task) {
            if (!$source || $source == $key) {

                $count = Collection::get($task, "count", 100);

                $output->writeln("Importing source ".$key." ");

                $parsed = $this->parseSource($task, $output, $verbose);

                $progress = new ProgressBar($output, min(count($parsed["items"]), $count));
                $progress->start();

                $imported = 0;
                foreach($parsed["items"] as $item){

                    if($imported >= $count)
                        break;

                    // Field values
                    $fields = Collection::get($task, "fields", []);
                    $values = $this->getValues($item, $parsed["channel"], $fields, "set");

                    // Taxonomies
                    $fields = Collection::get($task, "taxonomies", []);
                    $taxonomies = $this->getValues($item, $parsed["channel"], $fields, "add");

                    $this->importContent($values, $taxonomies, $task);

                    $progress->advance();
                    $imported ++;
                }

                $progress->finish();
            }
        }

        return;
    }

    protected function getValues($item, $channel, $fields, $mode){
        $values = [];
        foreach($fields as $field => $config){
            $config = $config + self::$defaultField;

            $value = $this->extractValues($item, $channel, $config);
            $value = $this->applyFilters($value, $config, $values);

            switch($mode){
                case "add":
                    if(!is_array($values[$field]))
                        $values[$field] = [];

                    if(is_array($value))
                        $values[$field] = array_merge($values[$field], $value);
                    else
                        $values[$field][] = $value;
                    break;
                case "set":
                    $values[$field] = $value;
                    break;
            }
        }
        return $values;
    }

    protected function parseSource($source, OutputInterface $output, $verbose){

        $format = Collection::get($source, "source.format", "rss2");
        $url = Collection::get($source, "source.url", false);
        $formatClass = dirname(__NAMESPACE__)."\\Format\\".ucfirst($format);

        if(!class_exists($formatClass))
            throw new Exception("Unknown format '".$format."' specified");

        if(!$url)
            throw new Exception("Invalid url '".$url."' specified");

        $Format = new $formatClass();

        if(!$Format instanceof IFormat)
            throw new Exception("Invalid format class '".$format."' specified");

        return $Format->parse($url);
    }

    protected function extractValues($item, $channel, $config){

        $data = ["item" => $item, "channel" => $channel];

        return Collection::get($data, $config["source"], $config["default"]);
    }

    protected function applyFilters($input, $config, $values){

        $output = $input;

        $filters = Collection::get($config, "filters", []);

        // Check if this is an associative array. This allows short notation: "filters: [first, second]" beside complex with parameters "filters: [first: [a,b], second: [cd]]"
        if( !(array_keys($filters) !== range(0, count($filters) - 1)) )
            $filters = array_flip($filters);

        foreach($filters as $filter => $params){

            $filterClass = dirname(__NAMESPACE__)."\\Filter\\".ucfirst($filter);

            if(!class_exists($filterClass))
                throw new Exception("Unknown filter '".$filter."' specified");

            $Filter = new $filterClass();

            if(!$Filter instanceof IFilter)
                throw new Exception("Invalid format class '".$filter."' specified");

            $output = $Filter->filter($output, $params, $this->app, $values);
        }

        return $output;
    }

    protected function importContent($values, $taxonomies, $config){
        $identifierField = Collection::get($config, "identifier", "guid");
        $identifier = Collection::get($values, $identifierField, sha1( serialize($values) ) );

        $contenttype = Collection::get($config, "contenttypeslug", false);
        $slugField = Collection::get($config, "slug", "title");
        $status = Collection::get($config, "status", "published");

        $repo = $this->app['storage']->getRepository($contenttype);

        /* @var Content $content */
        $content = $repo->findOneBy([$identifierField => $identifier]);
        if(!$content) {
            $content = $repo->create(['contenttype' => $contenttype, 'status' => $status]);

            $content->setSlug($this->app["slugify"]->slugify($values[$slugField]));
            $content->setDatecreated(new DateTime("now"));
        }

        $content->setDatechanged(new DateTime("now"));

        foreach($values as $key => $value){
            $content->set($key, $value);
        }

        /* @var Taxonomy $taxonomy */
        $taxonomy = $this->app['storage']->createCollection('Bolt\Storage\Entity\Taxonomy');
        $taxonomy->setFromPost($taxonomies, $content);
        $content->setTaxonomy($taxonomy);

        $repo->save($content);
    }

}