<?php
namespace Topolis\Bolt\Extension\ContentImport\Command;

use Bolt\Controller\Base;
use Bolt\Nut\BaseCommand;
use Silex\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Topolis\Bolt\Extension\ContentImport\Extension;

/**
 * An nut command for then KoalaCatcher extension.
 *
 * @author Kenny Koala
 * <kenny@dropbear.com.au>
 */
class Import extends Command {

    /**
     * @param \Silex\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    protected function configure() {
        $this
            ->setName('contentimport:import')
            ->setDescription('Import one or all content sources into Bolt')
            ->addOption(
                'source',
                's',
                InputArgument::OPTIONAL,
                'Only import this source',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $source = $input->getOption('source');
        $verbose = $input->getOption('verbose');

        $this->app[Extension::EXTID.".importer"]->import($source, $output, $verbose);
    }
}