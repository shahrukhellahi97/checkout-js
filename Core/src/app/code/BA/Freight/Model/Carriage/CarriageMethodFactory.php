<?php
namespace BA\Freight\Model\Carriage;

use Magento\Framework\App\ObjectManager;

class CarriageMethodFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param mixed $typeId
     * @param array $data
     * @return \BA\Freight\Api\FreightCalculatorInterface
     * @throws \LogicException
     * @throws \BadMethodCallException
     */
    public function create($typeId, array $data = [])
    {
        switch ($typeId) {
            case CarriageMethodInterface::FIXED:
                return $this->objectManager->create(\BA\Freight\Model\Carriage\Method\Fixed::class, $data);
            case CarriageMethodInterface::UPLIFT:
                return $this->objectManager->create(\BA\Freight\Model\Carriage\Method\Uplift::class, $data);
            case CarriageMethodInterface::MATRIX:
                return $this->objectManager->create(\BA\Freight\Model\Carriage\Method\Matrix::class, $data);
        }
    }
}
