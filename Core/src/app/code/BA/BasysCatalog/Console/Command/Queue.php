<?php
namespace BA\BasysCatalog\Console\Command;

use BA\BasysCatalog\Api\ConsoleManagementInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class Queue extends Command
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
        $this->setName('ba:catalog:queue');

        $this->addOption('catalog', 'c', InputArgument::OPTIONAL);
        $this->addOption('division', 'd', InputArgument::OPTIONAL);
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $catalogId = $input->getOption('catalog');
        $divisionId = $input->getOption('division');

        $this->consoleManagement->setOutput($output);
        $this->consoleManagement->queue($divisionId, $catalogId);
    }
}
