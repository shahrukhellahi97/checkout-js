<?php
namespace BA\BasysCatalog\Import\Product;

use BA\BasysCatalog\Import\Product\Type\Configurable;
use BA\BasysCatalog\Import\Product\Type\Grouped;
use BA\BasysCatalog\Import\Product\Type\Simple;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;

class ProductTypeFactory
{
    const TYPE_SIMPLE  = 1;
    
    const TYPE_GROUPED = 2;

    const TYPE_CONFIGURABLE = 3;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param int $typeId 
     * @return \BA\BasysCatalog\Import\Product\ProductCreationInterface 
     * @throws \Magento\Framework\Exception\LocalizedException 
     */
    public function create(int $typeId)
    {
        switch ($typeId) {
            case self::TYPE_SIMPLE:
                return $this->objectManager->create(Simple::class);
            case self::TYPE_GROUPED:
                return $this->objectManager->create(Grouped::class);
            case self::TYPE_CONFIGURABLE:
                return $this->objectManager->create(Configurable::class);
            default:
                throw new LocalizedException(__("Unknown type"));
        }
    }
}