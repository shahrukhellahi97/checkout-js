<?php
namespace BA\Theme\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductViewModel implements ArgumentInterface
{
    private $_commonSubstring;

    /**
     * Get product size from product
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Catalog\Model\Product[]|array $products
     * @todo refactor to handle attributes
     * @return string
     */
    public function getProductSize(Product $product, array $products)
    {
        $productName = $product->getName();
        $substring  = $this->getLongestCommonSubstring(array_map(function ($product) {
            return $product->getName();
        }, $products));

        return strtolower($productName) != $substring ?
            preg_replace('/^' . preg_quote($substring) . '/i', '', $productName) :
            $productName;
    }

    public function getProductAttributes(array $products)
    {
        $result = [];
        $defaultAttributes = [
            'quantity_and_stock_status',
        ];

        // /** @var \Magento\Catalog\Model\Product $product */
        // foreach ($products as $product) {
            
        //     $attributes = $product->getAttributes();

        //     foreach ($defaultAttributes as $attributeCode) {
        //         if (isset($attributes[$attributeCode])) {
        //             /** @var \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute */
        //             $attribute = $attributes[$attributeCode];
        //             $label = $attributes[$attributeCode]->getDefaultFrontendLabel();

        //             $result[$attribute->getFrontend()->getLocalizedLabel()] = $attribute->get
        //         }
        //     }
        //     // foreach ($defaultAttributes as $attr) {
        //     //     $product->
        //     // }
        // }

        return $result;
    }

    /**
     * @source https://gist.github.com/chrisbloom7/1021218
     * @param array $words
     * @return string
     */
    private function getLongestCommonSubstring($words)
    {
        if ($this->_commonSubstring == null) {
            $words = array_map('strtolower', array_map('trim', $words));
            $sort = function ($a, $b) {
                if (strlen($a) == strlen($b)) {
                    return strcmp($a, $b);
                } else {
                    return strlen($a) < strlen($b) ? -1 : 1;
                }
            };

            usort($words, $sort);

            $longest_common_substring = [];
            $shortest_string = str_split(array_shift($words));
            while (count($shortest_string)) {
                array_unshift($longest_common_substring, '');
                foreach ($shortest_string as $ci => $char) {
                    foreach ($words as $wi => $word) {
                        if (!strstr($word, $longest_common_substring[0] . $char)) {
                            break 2;
                        }
                    }
                    $longest_common_substring[0].= $char;
                }

                array_shift($shortest_string);
            }

            usort($longest_common_substring, $sort);

            $this->_commonSubstring = array_pop($longest_common_substring);
        }

        return $this->_commonSubstring;
    }
}
