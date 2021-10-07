<?php
namespace BA\BasysCatalog\Api;

use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleManagementInterface
{
    /**
     * Clean database
     * 
     * @return void
     */
    public function clean();

    /**
     * @param int $divisionId 
     * @param int|null $catalogId 
     * @param int $size 
     * @return void 
     */
    public function process($divisionId, $catalogId = null, $size = 50);

    /**
     * @param int $divisionId 
     * @param int|null $catalogId 
     * @return void
     */
    public function queue($divisionId, $catalogId = null);

    public function setOutput(OutputInterface $output);
}