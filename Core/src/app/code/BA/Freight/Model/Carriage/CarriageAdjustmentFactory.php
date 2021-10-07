<?php
namespace BA\Freight\Model\Carriage;

class CarriageAdjustmentFactory
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
     * @return \BA\Freight\Model\Carriage\CarriageAdjustmentInterface
     */
    public function create($typeId, array $data = [])
    {
        switch ($typeId) {
            case CarriageModelInterface::WEIGHT:
                return $this->objectManager->create(\BA\Freight\Model\Carriage\Adjustment\Weight::class, $data);
            case CarriageModelInterface::VALUE:
                return $this->objectManager->create(\BA\Freight\Model\Carriage\Adjustment\Value::class, $data);
        }
    }
}
