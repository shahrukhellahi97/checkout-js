<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\QuoteManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ProductFactoryResource;

class QuoteManagement implements QuoteManagementInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\ProductFactory
     */
    protected $productFactoryResource;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $resource;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        ProductFactory $productFactory,
        ProductFactoryResource $productFactoryResource,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productFactory = $productFactory;
        $this->productFactoryResource = $productFactoryResource;
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(\Magento\Quote\Model\Quote $quote)
    {
        $result = [];

        foreach ($quote->getAllVisibleItems() as $item) {
            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());

            if (count($options) > 1) {
                if (isset($options['bundle_options'])) {
                    /** @var \Magento\Bundle\Model\Product\Type $bundled */
                    $bundled = $item->getProduct()->getTypeInstance(true);
                    $selections = $bundled->getSelectionsCollection(
                        array_keys($options['bundle_options']),
                        $item->getProduct()
                    );

                    $selectedSkus = [];

                    foreach ($options['info_buyRequest']['bundle_option'] as $optionId => $selectionId) {
                        if (is_array($selectionId)) {
                            foreach ($selectionId as $id) {
                                $selectedSkus[$optionId][$id] = '';
                            }
                        } else {
                            $selectedSkus[$optionId][$selectionId] = '';
                        }
                    }

                    foreach ($selections->getItems() as $selection) {
                        if (isset($selectedSkus[$selection->getOptionId()]) &&
                            isset($selectedSkus[$selection->getOptionId()][$selection->getSelectionId()])
                        ) {
                            $selectedSkus[$selection->getOptionId()][$selection->getSelectionId()] = $selection->getSku();
                        }
                    }

                    foreach ($selectedSkus as $option => $selections) {
                        foreach ($selections as $sku) {
                            $result[] = $this->productRepository->get($sku);
                        }
                    }
                } elseif (isset($options['simple_sku'])) {
                    $result[] = $this->productRepository->get($options['simple_sku']);
                }
            } else {
                $result[] = $item->getProduct();
            }
        }

        return $result;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return \Magento\Catalog\Model\Product
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProduct(\Magento\Quote\Model\Quote\Item $item)
    {
        $children = $item->getChildren();

        if (count($children) >= 1) {
            return $children[0]->getProduct();
        }

        return $item->getProduct();
    }

    private function getResource()
    {
        if (!isset($this->resource)) {
            $this->resource = $this->productFactoryResource->create();
        }

        return $this->resource;
    }
}
