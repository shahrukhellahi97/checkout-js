<?php
namespace BA\BasysCatalog\Import\Queue\Process;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\Modifier\ModifierInterface;
use BA\BasysCatalog\Import\Product\ProductCreationInterface;
use BA\BasysCatalog\Import\Product\ProductTypeFactory;
use BA\BasysCatalog\Import\Product\SaveHandlerInterface;
use BA\BasysCatalog\Import\Queue\QueueListResultInterface;
use BA\BasysCatalog\Import\Queue\QueueProcessorInterface;
use BA\BasysCatalog\Import\VersionValidationInterface;
use Magento\Catalog\Api\Data\ProductLinkInterfaceFactory;
use Magento\Catalog\Model\ProductRepository;

class SimpleProcessor implements QueueProcessorInterface
{
    /**
     * @var \BA\BasysCatalog\Import\Product\ProductTypeFactory
     */
    protected $productTypeFactory;

    /**
     * @var \BA\BasysCatalog\Import\Product\Modifier\ModifierInterface
     */
    protected $modifier;

    /**
     * @var \BA\BasysCatalog\Import\Product\SaveHandlerInterface
     */
    protected $saveHandler;

    /**
     * @var \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory
     */
    protected $productLinkFactory;

    /**
     * @var \BA\BasysCatalog\Import\VersionValidationInterface
     */
    protected $versionValidator;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \BA\BasysCatalog\Api\Data\BasysProductInterface[]
     */
    protected $productCache = [];

    public function __construct(
        ProductTypeFactory $productTypeFactory,
        ModifierInterface $modifier,
        SaveHandlerInterface $saveHandler,
        ProductLinkInterfaceFactory $productLinkFactory,
        ProductRepository $productRepository,
        VersionValidationInterface $versionValidator
    ) {
        $this->productTypeFactory = $productTypeFactory;
        $this->saveHandler = $saveHandler;
        $this->productLinkFactory = $productLinkFactory;
        $this->versionValidator = $versionValidator;
        $this->productRepository = $productRepository;
        $this->modifier = $modifier;
    }

    public function process(QueueListResultInterface $queueResult, callable $callback = null)
    {
        $groups = [];
        $versions = [];
        $items = [];

        /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $queueItem */
        foreach ($queueResult->getAll() as $queueItem) {
            // Group items by report SKU
            $groups[$queueItem->getReportSku()][$queueItem->getSku()] = $queueItem;

            // Group items by SKU
            $items[$queueItem->getSku()] = $queueItem;

            // Add version information
            $versions[$queueItem->getSku()] = [
                'cid' => $queueItem->getCatalogId(),
                'pid' => $queueItem->getBasysId(),
                'ver' => $queueItem->getVersion()
            ];
        }

        foreach ($groups as $group) {
            /** @var \Magento\Catalog\Model\Product $product */
            foreach ($this->getAllProducts($group) as $product) {
                $basysProduct = $product->getTypeId() === 'simple' ? $items[$product->getSku()] : null;
                
                $this->saveHandler->save($this->modifier->apply($product, $basysProduct));

                if ($product->getTypeId() === 'simple' && $callback != null) {
                    $callback($basysProduct, $product);
                }
            }
        }
    }

    /**
     * @param array $group
     * @return \Magento\Catalog\Api\Data\ProductInterface[]|array
     */
    protected function getAllProducts(array $group)
    {
        $result = [];
        $group  = array_values($group);

        foreach ($group as $product) {
            $result[] = $this->create($product, ProductTypeFactory::TYPE_SIMPLE);
        }

        if (count($group) > 1) {
            /** @var \Magento\Catalog\Model\Product $groupedProduct */
            $groupedProduct = $this->create($group[0], ProductTypeFactory::TYPE_GROUPED);
            $groupedProductLinks = [];

            /** @var \Magento\Catalog\Model\Product $product */
            foreach ($result as $product) {
                $product->setVisibility(1);

                /** @var \Magento\Catalog\Api\Data\ProductLinkInterface $link */
                $link = $this->productLinkFactory->create();

                $link->setSku($groupedProduct->getSku())
                    ->setLinkType('associated')
                    ->setLinkedProductSku($product->getSku())
                    ->setLinkedProductType($product->getTypeId())
                    ->getExtensionAttributes()
                    ->setQty(0);

                $groupedProductLinks[] = $link;
            }

            $groupedProduct->setProductLinks($groupedProductLinks);
            $result[] = $groupedProduct;
        }

        return $result;
    }

    public function create(BasysProductInterface $product, int $type)
    {
        $creation = $this->productTypeFactory->create($type);

        /** @var \Magento\Catalog\Model\Product $newProduct */
        $newProduct = $creation->create($product);
        
        try {
            $product = $this->productRepository->get($newProduct->getSku(), true);

            $product->setName($newProduct->getName())
                ->setWeight($newProduct->getWeight())
                ->setVisibility(4);

            return $product;
        } catch (\Exception $e) {
            return $newProduct;
        }
    }
}
