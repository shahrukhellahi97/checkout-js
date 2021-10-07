<?php
namespace BA\BasysGiftCertificate\Console\Command;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use Symfony\Component\Console\Command\Command;
//use BA\BasysStore\Helper\Data;
use Psr\Log\LoggerInterface;

class CheckBalance extends Command
{
    protected $commandPool;
    protected $activeCatalog;
    protected $logger;

    public function __construct(
        CommandPoolInterface $commandPool,
        LoggerInterface $logger
     //   Data $activeCatalog
    ) {
        parent::__construct();
        $this->commandPool = $commandPool;
      //  $this->activeCatalog = $activeCatalog;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('basys:check-balance');
        $this->setDescription("Check gift certificate balance");
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
      //  $activeCatalogId = $this->activeCatalog->getActiveCatalogIds();
        $command = $this->commandPool->get('check_balance');
        $command->execute([
            'CheckBalance' => [
                'CertificateReference' => '0218-F401-12ED-8263',
                'Currency' => 'EUR',
                'DivisionID' => '218'
            ],
        ]);
    }
}
