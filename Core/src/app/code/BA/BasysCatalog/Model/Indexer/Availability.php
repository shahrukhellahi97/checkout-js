<?php
namespace BA\BasysCatalog\Model\Indexer;

use Magento\Framework\App\ResourceConnection;

class Availability implements
    \Magento\Framework\Indexer\ActionInterface,
    \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var \BA\BasysCatalog\Model\Indexer\Availability\Action\Full
     */
    protected $indexerFull;

    /**
     * @var \BA\BasysCatalog\Model\Indexer\Availability\Action\Rows
     */
    protected $indexerRows;

    /**
     * @var \BA\BasysCatalog\Model\Indexer\Availability\Action\Row
     */
    protected $indexerRow;


    public function __construct(
        \BA\BasysCatalog\Model\Indexer\Availability\Action\Full $indexerFull,
        \BA\BasysCatalog\Model\Indexer\Availability\Action\Rows $indexerRows,
        \BA\BasysCatalog\Model\Indexer\Availability\Action\Row $indexerRow
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
        $this->indexerRows->execute([]);
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
