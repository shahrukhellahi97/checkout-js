<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface MoneyInterface
{
    const CURRENCY = 'currency';

    const VALUE = 'value';    

    /**
     * @return float
     */
    public function getValue();

    /**
     * @param float $value 
     * @return self
     */
    public function setValue(float $value);

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @param string $currency 
     * @return self
     */
    public function setCurrency(string $currency);
}