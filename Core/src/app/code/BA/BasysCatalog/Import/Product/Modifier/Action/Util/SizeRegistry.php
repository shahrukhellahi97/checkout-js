<?php
namespace BA\BasysCatalog\Import\Product\Modifier\Action\Util;

class SizeRegistry
{
    private const SIZES = [
        1 => 'XS',
        2 => 'S',
        3 => 'M',
        4 => 'L',
        5 => 'XL',
        6 => 'XXL',
        7 => 'XXXL',
    ];

    public function getAllSizes()
    {
        return self::SIZES;
    }

    public function getSizeFromVariant($variant): ?int
    {
        foreach (self::SIZES as $id => $size) {
            if (strtoupper($size) == strtoupper($variant)) {
                return (int) $id;
            }
        }

        return null;
    }
}