<?php
namespace BA\BasysCatalog\Model\Indexer;

use BA\BasysCatalog\Api\ConsoleManagementInterface;
use Magento\Framework\App\ResourceConnection;

class Mapping implements
    \Magento\Framework\Indexer\ActionInterface,
    \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var \BA\BasysCatalog\Model\Indexer\Mapping\Action\Full
     */
    protected $indexerFull;

    /**
     * @var \BA\BasysCatalog\Model\Indexer\Mapping\Action\Rows
     */
    protected $indexerRows;

    /**
     * @var \BA\BasysCatalog\Model\Indexer\Mapping\Action\Row
     */
    protected $indexerRow;


    public function __construct(
        \BA\BasysCatalog\Model\Indexer\Mapping\Action\Full $indexerFull,
        \BA\BasysCatalog\Model\Indexer\Mapping\Action\Rows $indexerRows,
        \BA\BasysCatalog\Model\Indexer\Mapping\Action\Row $indexerRow
    ) {
        $this->indexerFull = $indexerFull;
        $this->indexerRows = $indexerRows;
        $this->indexerRow = $indexerRow;
    }

    public function execute($ids)
    {
        $this->indexerRows->execute($ids);
    }

    public function executeFull()
    {
        $this->indexerFull->execute([]);
    }

    public function executeList(array $ids)
    {
        $this->indexerRows->execute($ids);
    }

    public function executeRow($id)
    {
        $this->indexerRow->execute($id);
    }
}