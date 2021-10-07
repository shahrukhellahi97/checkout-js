<?php
namespace BA\Theme\Model\Product;

interface ProductAttributeInterface
{
    /**
     * @return string 
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getValue(): string;
}