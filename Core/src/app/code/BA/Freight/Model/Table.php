<?php
namespace BA\Freight\Model;

use BA\Freight\Api\Data\TableInterface;
use BA\Freight\Model\ResourceModel\Table as ResourceModelTable;
use BA\Freight\Model\ResourceModel\ZoneFactory;
use Magento\Framework\Model\AbstractModel;

class Table extends AbstractModel implements TableInterface
{
    /**
     * @var \BA\Freight\Model\ResourceModel\ZoneFactory
     */
    protected $zoneFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ZoneFactory $zoneFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->zoneFactory = $zoneFactory;
    }

    protected function _construct()
    {
        $this->_init(ResourceModelTable::class);
    }

    public function getId()
    {
        return $this->getData(TableInterface::TABLE_ID);
    }

    public function setId($tableId)
    {
        return $this->setData(TableInterface::TABLE_ID, $tableId);
    }

    public function getName()
    {
        return $this->getData(TableInterface::NAME);
    }

    public function setName($name)
    {
        return $this->setData(TableInterface::NAME, $name);
    }

    public function getRate($countryId, $weight = 0.00)
    {
        // todo: refactor
        /** @var \BA\Freight\Model\ResourceModel\Zone $resource */
        $resource = $this->zoneFactory->create();

        return $resource->getRateForWeight($this->getId(), $countryId, $weight);
    }
}
