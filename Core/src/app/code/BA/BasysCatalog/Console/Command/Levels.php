<?php
namespace BA\BasysCatalog\Console\Command;

use BA\BasysCatalog\Api\ConsoleManagementInterface;
use BA\BasysCatalog\Cron\Levels as CronLevels;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Levels extends Command
{
    /**
     * @var \BA\BasysCatalog\Api\ConsoleManagementInterface
     */
    protected $consoleManagement;

    /**
     * @var \BA\BasysCatalog\Cron\Levels
     */
    protected $levels;

    public function __construct(
        ConsoleManagementInterface $consoleManagement,
        CronLevels $levels
    ) {
        parent::__construct();

        $this->consoleManagement = $consoleManagement;
        $this->levels = $levels;
    }

    protected function configure()
    {
        $this->setName('ba:catalog:levels');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Pulling Levels');
        
        $this->levels->execute();

        $output->writeln('<info>Complete</info>');
    }
}
