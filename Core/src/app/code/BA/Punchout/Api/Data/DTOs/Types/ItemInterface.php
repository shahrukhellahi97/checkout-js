<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface ItemInterface extends AttributeCollectionInterface
{
    const SUPPLIER_PART_ID = 'supplier_part_id';

    const DESCRIPTION = 'description';

    const QUANTITY = 'quantity';

    const UNIT_PRICE = 'unit_price';

    const CLASSIFICATION = 'classification';

    /**
     * @return string
     */
    public function getSupplierPartId();

    /**
     * @param string $sku 
     * @return self
     */
    public function setSupplierPartId(string $sku);

    /**
     * @return self
     */
    public function getDescription();

    /**
     * @param string $description 
     * @return self
     */
    public function setDescription(string $description);

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @param int $quantity 
     * @return self
     */
    public function setQuantity(int $quantity);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\MoneyInterface|null
     */
    public function getUnitPrice();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\MoneyInterface $unitPrice 
     * @return mixed 
     */
    public function setUnitPrice(MoneyInterface $unitPrice);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\ClassificationInterface|null
     */
    public function getClassification();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\ClassificationInterface $classificationInterface 
     * @return self
     */
    public function setClassification(ClassificationInterface $classificationInterface);
}