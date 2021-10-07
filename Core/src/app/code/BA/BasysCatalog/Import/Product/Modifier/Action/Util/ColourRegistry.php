<?php
namespace BA\BasysCatalog\Import\Product\Modifier\Action\Util;

use Magento\Catalog\Model\ResourceModel\Product as ProductResource;

class ColourRegistry
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResource;

    private const COLOUR_MAP = [
        '/red|maroon/i' => 'Red',
        '/orange/i' => 'Orange',
        '/yellow/i' => 'Yellow',
        '/green|turquoise|lime/i' => 'Green',
        '/blue|cyan|navy|aqua|teal/i' => 'Blue',
        '/purple|indigo|violet/i' => 'Purple',
        '/white/i' => 'White',
        '/black/i' => 'Black',
        '/brown|tan|olive/i' => 'Brown',
        '/pink|magenta|lavender/i' => 'Pink',
        '/gr[ae]y/i' => 'Gray',
    ];

    /**
     * @var array
     */
    private $_attributes = [];

    public function __construct(ProductResource $productResource)
    {
        $this->productResource = $productResource;
    }

    /**
     * Get colour from product name
     *
     * @param string $productName
     * @return null|string
     */
    public function getColourFromName($productName): ?string
    {
        foreach (self::COLOUR_MAP as $pattern => $colour) {
            if (preg_match($pattern, $productName)) {
                return $this->getAttributeId($colour);
            }
        }

        return null;
    }

    /**
     * @param mixed $colour
     * @return null|int
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \ValueError
     */
    private function getAttributeId($colour): ?int
    {
        if (!isset($this->_attributes[$colour])) {
            $attr = $this->productResource->getAttribute('color');

            $this->_attributes[$colour] = $attr->getSource()->getOptionId($colour);
        }

        return $this->_attributes[$colour];
    }
}
