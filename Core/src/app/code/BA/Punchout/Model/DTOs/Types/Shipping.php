<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\AddressInterface;
use BA\Punchout\Api\Data\DTOs\Types\MoneyInterface;
use BA\Punchout\Api\Data\DTOs\Types\ShippingInterface;

class Shipping extends AbstractType implements ShippingInterface
{
    /**
     * @var \BA\Punchout\Model\DTOs\Types\MoneyFactory
     */
    protected $moneyFactory;

    /**
     * @var \BA\Punchout\Model\DTOs\Types\AddressFactory
     */
    protected $addressFactory;

    public function __construct(
        \BA\Punchout\Model\DTOs\Types\MoneyFactory $moneyFactory,
        \BA\Punchout\Model\DTOs\Types\AddressFactory $addressFactory,
        array $data = []
    ) {
        $this->moneyFactory = $moneyFactory;
        $this->addressFactory = $addressFactory;

        parent::__construct($data);
    }

    public function getTotal()
    {
        if (!$this->hasData(ShippingInterface::TOTAL)) {
            $this->setTotal($this->moneyFactory->create());
        }

        return $this->getData(ShippingInterface::TOTAL);
    }

    public function setTotal(MoneyInterface $value)
    {
        return $this->setData(ShippingInterface::TOTAL, $value);
    }

    public function getDescription()
    {
        return $this->getData(ShippingInterface::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(ShippingInterface::DESCRIPTION, $description);
    }

    public function getShipTo()
    {
        if (!$this->hasData(ShippingInterface::SHIP_TO)) {
            $this->setShipTo($this->addressFactory->create());
        }

        return $this->getData(ShippingInterface::SHIP_TO);
    }

    public function setShipTo(AddressInterface $address)
    {
        return $this->setData(ShippingInterface::SHIP_TO, $address);
    }

}