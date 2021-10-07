<?php
namespace BA\BasysOrders\Gateway\Request;

use BA\BasysCatalog\Api\ProductResolverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class OrderDataBuilder implements BuilderInterface
{
    /**
     * @var \BA\BasysCatalog\Api\ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        ProductResolverInterface $productResolver,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productResolver = $productResolver;
        $this->productRepository = $productRepository;
    }

    public function build(array $buildSubject)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $buildSubject['payment']->getPayment()->getOrder();

        $x = [
            'Order' => [
                'OrderHeader' => [
                    'OrderCurrency' => $order->getOrderCurrencyCode(),
                    'GoodsTotal' => $order->getGrandTotal(),
                    'Freight' => $order->getShippingInclTax(),
                    'Tax' => $order->getTaxAmount(),
                ],
                'OrderLines' => $this->buildOrderLines($order)
            ]
        ];

        return $x;
    }

    private function buildOrderLines($order)
    {
        $result = [];

        foreach ($order->getAllVisibleItems() as $item) {
            $product = $this->productRepository->getById($item->getProductId());

            /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $basys */
            $basys = $this->productResolver->get($product);

            $result[] = [
                'OrderLine' => [
                    'CatalogueID' => $basys->getCatalogId(),
                    'CatalogueAlias' => $basys->getSku(),
                    'UnitPrice'   => $item->getPrice(),
                    'LineQty' => $item->getQtyOrdered(),
                    'ProductID' => $basys->getBasysId(),
                    'BaseColour' => $basys->getBaseColour(),
                    'TrimColour' => $basys->getTrimColour(),
                    'SupplierID' => 4746
                ]
            ];
        }

        return $result;
    }
}
