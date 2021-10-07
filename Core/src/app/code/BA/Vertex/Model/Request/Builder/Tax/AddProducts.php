<?php
namespace BA\Vertex\Model\Request\Builder\Tax;

use BA\BasysCatalog\Api\ProductResolverInterface;
use BA\Vertex\Model\Request\Builder\CalculateTaxRequestInterface;
use Magento\Quote\Model\Quote;

class AddProducts implements CalculateTaxRequestInterface
{
    /**
     * @var \BA\BasysCatalog\Api\ProductResolverInterface
     */
    protected $productResolver;

    public function __construct(
        ProductResolverInterface $productResolver
    ) {
        $this->productResolver = $productResolver;
    }

    public function build(Quote $quote)
    {
        return [
            'Destinations' => [
                'Destination' => [
                    'Products' => $this->getProducts($quote->getAllVisibleItems())
                ]
            ]
        ];
    }

    /**
     * Build product tree
     *
     * @param \Magento\Quote\Model\Quote\Item[]|array $items
     * @return array
     */
    private function getProducts(array $items)
    {
        $result = [];

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($items as $item) {
            $children = $item->getChildren();

            if (count($children) >= 1) {
                foreach ($children as $child) {
                    $product = $this->productResolver->get($child->getProduct());

                    $result = $this->addNode(
                        $result,
                        $product->getBasysId(),
                        $child->getParentItem()->getRowTotal()
                    );
                }
            } else {
                $product = $this->productResolver->get($item->getProduct());
                $result = $this->addNode($result, $product->getBasysId(), $item->getRowTotal());
            }
        }

        return $result;
    }

    private function addNode(array $result, $basysId, $rowTotal)
    {
        $result[] = [
            'Product' => [
                'ProductID'  => $basysId,
                'TotalSales' => $rowTotal,
                'SourceCountry' => [
                    'GB',
                    'United Kingdom'
                ],
            ]
        ];

        return $result;
    }
}
