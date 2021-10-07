<?php
namespace BA\Punchout\Api\Data\DTOs\Types;


interface ShippingInterface
{
    const TOTAL = 'total';

    const DESCRIPTION = 'description';

    const SHIP_TO = 'ship_to';

    /**
     * @return \BA\Punchout\Money\MoneyInterface
     */
    public function getTotal();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\MoneyInterface $value 
     * @return self
     */
    public function setTotal(MoneyInterface $value);

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @param string $description 
     * @return self
     */
    public function setDescription($description);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\AddressInterface 
     */
    public function getShipTo();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\AddressInterface $address 
     * @return self
     */
    public function setShipTo(AddressInterface $address);
}