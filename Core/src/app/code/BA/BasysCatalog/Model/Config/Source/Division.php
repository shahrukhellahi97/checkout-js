<?php
namespace BA\BasysCatalog\Model\Config\Source;

use BA\BasysCatalog\Model\ResourceModel\Division as ResourceModelDivision;
use BA\BasysCatalog\Model\ResourceModel\Division\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Division implements OptionSourceInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Division\CollectionFactory
     */
    protected $divisionCollectionFactory;

    public function __construct(CollectionFactory $divisionCollectionFactory)
    {
        $this->divisionCollectionFactory = $divisionCollectionFactory;
    }

    public function toOptionArray()
    {
        $collection = $this->divisionCollectionFactory->create();

        $divisions = $collection->load();
        $result = [];

        /** @var BA\BasysCatalog\Model\Division $division */
        foreach ($divisions as $division) {
            $result[] = [
                'label' => sprintf("%s ( ID: %s )", $division->getName(), $division->getId()),
                'value' => $division->getId(),
            ];
        }

        return $result;
    }
}