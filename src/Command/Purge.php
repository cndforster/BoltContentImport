<?php
namespace Topolis\Bolt\Extension\ContentImport\Command;

use Pimple as Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Topolis\Bolt\Extension\ContentImport\Extension;

/**
 * An nut command for then KoalaCatcher extension.
 *
 * @author Kenny Koala
 * <kenny@dropbear.com.au>
 */
class Purge extends Command {

    protected $app;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    protected function configure() {
        $this
            ->setName('contentimport:purge')
            ->setDescription('Purge selected content older than X days or above limit')
            ->addOption(
                'source',
                's',
                InputArgument::OPTIONAL,
                'Only purge this source',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $source = $input->getOption('source');
        $verbose = $input->getOption('verbose');

        $this->app[Extension::EXTID.".importer"]->purge($source, $output, $verbose);
    }
}