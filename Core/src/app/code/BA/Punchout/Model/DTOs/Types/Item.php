<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\ClassificationInterface;
use BA\Punchout\Api\Data\DTOs\Types\ItemInterface;
use BA\Punchout\Api\Data\DTOs\Types\MoneyInterface;

class Item extends AttributeCollection implements ItemInterface
{
    /**
     * @var \BA\Punchout\Model\DTOs\Types\MoneyFactory
     */
    protected $moneyFactory;

    /**
     * @var \BA\Punchout\Model\DTOs\Types\ClassificationFactory
     */
    protected $classificationFactory;

    public function __construct(
        \BA\Punchout\Model\DTOs\Types\AttributeFactory $attributeFactory,
        \BA\Punchout\Model\DTOs\Types\MoneyFactory $moneyFactory,
        \BA\Punchout\Model\DTOs\Types\ClassificationFactory $classificationFactory,
        array $data = []
    ) {
        $this->moneyFactory = $moneyFactory;
        $this->classificationFactory = $classificationFactory;

        parent::__construct($attributeFactory, $data);
    }

    public function getSupplierPartId()
    {
        return $this->getData(ItemInterface::SUPPLIER_PART_ID);
    }

    public function setSupplierPartId(string $sku)
    {
        return $this->setData(ItemInterface::SUPPLIER_PART_ID, $sku);
    }

    public function getDescription()
    {
        return $this->getData(ItemInterface::DESCRIPTION);
    }

    public function setDescription(string $description)
    {
        return $this->setData(ItemInterface::DESCRIPTION, $description);
    }

    public function getQuantity()
    {
        return $this->getData(ItemInterface::QUANTITY);
    }

    public function setQuantity(int $quantity)
    {
        return $this->setData(ItemInterface::QUANTITY, $quantity);
    }

    public function getUnitPrice()
    {
        if (!$this->hasData(ItemInterface::UNIT_PRICE)) {
            $this->setUnitPrice($this->moneyFactory->create());
        }
        
        return $this->getData(ItemInterface::UNIT_PRICE);
    }

    public function setUnitPrice(MoneyInterface $unitPrice)
    {
        return $this->setData(ItemInterface::UNIT_PRICE, $unitPrice);
    }

    public function getClassification()
    {
        if (!$this->hasData(ItemInterface::CLASSIFICATION)) {
            $this->setClassification($this->classificationFactory->create());
        }

        return $this->getData(ItemInterface::CLASSIFICATION);
    }

    public function setClassification(ClassificationInterface $classification)
    {
        return $this->setData(ItemInterface::CLASSIFICATION, $classification);
    }
}