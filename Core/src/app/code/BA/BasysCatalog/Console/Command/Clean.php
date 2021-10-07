<?php
namespace BA\BasysCatalog\Console\Command;

use BA\BasysCatalog\Api\ConsoleManagementInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Clean extends Command
{
    /**
     * @var \BA\BasysCatalog\Api\ConsoleManagementInterface
     */
    protected $consoleManagement;

    public function __construct(
        ConsoleManagementInterface $consoleManagement
    ) {
        parent::__construct();

        $this->consoleManagement = $consoleManagement;
    }

    protected function configure()
    {
        $this->setName('ba:catalog:clean');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Cleaning tables');
        
        $this->consoleManagement->setOutput($output);
        $this->consoleManagement->clean();

        $output->writeln('<info>Complete</info>');
    }
}
