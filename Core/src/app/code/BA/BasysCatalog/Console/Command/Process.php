<?php
namespace BA\BasysCatalog\Console\Command;

use BA\BasysCatalog\Api\ConsoleManagementInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class Process extends Command
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
        $this->setName('ba:catalog:process');

        $this->addOption('division', 'd', InputArgument::OPTIONAL);
        $this->addOption('catalog', 'c', InputArgument::OPTIONAL);
        $this->addOption('size', 's', InputArgument::OPTIONAL, '', 50);
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $catalogId  = $input->getOption('catalog');
        $divisionId = $input->getOption('division');
        $size = $input->getOption('size');
        
        $this->consoleManagement->setOutput($output);
        $this->consoleManagement->process($divisionId, $catalogId, $size);
    }
}
