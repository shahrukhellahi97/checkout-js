<?php
namespace BA\Freight\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use BA\Freight\Model\ResourceModel\Table\CollectionFactory;

class Table implements OptionSourceInterface
{
    /**
     * @var \BA\Freight\Model\ResourceModel\Table\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        /** @var \BA\Freight\Model\ResourceModel\Table\Collection $collection */
        $collection = $this->collectionFactory->create();
        $tables = $collection->load();

        $result = [];

        /** @var \BA\Freight\Model\Table $table */
        foreach ($tables as $table) {
            $result[] = [
                'label' => $table->getName(),
                'value' => $table->getId()
            ];
        }

        return $result;
    }
}
