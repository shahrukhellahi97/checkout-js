<?php
namespace BA\BasysCatalog\Import\Queue\Process;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\BatchSaveInterface;
use BA\BasysCatalog\Import\Product\Modifier\ModifierInterface;
use BA\BasysCatalog\Import\Product\ProductMetadataProviderInterface;
use BA\BasysCatalog\Import\Product\ProductTypeFactory;
use BA\BasysCatalog\Import\Queue\QueueListResultInterface;
use BA\BasysCatalog\Import\Queue\QueueProcessorInterface;
use Magento\Catalog\Api\Data\ProductLinkInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\ResourceConnectionFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;

class FastProcessor implements QueueProcessorInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnectionFactory
     */
    protected $resourceConnectionFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \BA\BasysCatalog\Import\Product\ProductTypeFactory
     */
    protected $productTypeFactory;

    /**
     * @var \BA\BasysCatalog\Import\Product\BatchSaveInterface
     */
    protected $batchSave;

    /**
     * @var \BA\BasysCatalog\Import\Product\Modifier\ModifierInterface
     */
    protected $modifier;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResource;

    /**
     * @var \BA\BasysCatalog\Import\Queue\Process\ProductLinkInterfaceFactory
     */
    protected $productLinkFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \BA\BasysCatalog\Import\Product\ProductMetadataProviderInterface
     */
    protected $productMetadata;

    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        ProductTypeFactory $productTypeFactory,
        ProductFactory $productFactory,
        ProductResourceModel $productResource,
        ProductRepositoryInterface $productRepository,
        ProductLinkInterfaceFactory $productLinkFactory,
        BatchSaveInterface $batchSave,
        ModifierInterface $modifier,
        ProductMetadataProviderInterface $productMetadata
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->productFactory = $productFactory;
        $this->productResource = $productResource;
        $this->productRepository = $productRepository;
        $this->productTypeFactory = $productTypeFactory;
        $this->productLinkFactory = $productLinkFactory;
        $this->batchSave = $batchSave;
        $this->modifier = $modifier;
        $this->productMetadata = $productMetadata;
    }

    public function process(QueueListResultInterface $queue, callable $callback = null)
    {
        $skusProcessed = [];

        $skus = array_map(function ($product) {
            return $product->getSku();
        }, $queue->getAll());

        $existingSkus = $this->getExistingProducts($skus);

        $groups = [];

        /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $basysProduct */
        foreach ($queue->getAll() as $basysProduct) {
            if (!in_array($basysProduct->getSku(), $skusProcessed)) {
                if ($id = array_search($basysProduct->getSku(), $existingSkus)) {
                    $product = $this->productFactory->create();
                    $this->productResource->load($product, $id);

                    // Only update description and name
                    $product->setName($basysProduct->getTitle());
                    $product->setDescription($basysProduct->getDescription());

                    $callback($basysProduct, $product);
                } else {
                    /** @var \Magento\Catalog\Model\Product $product */
                    $productFactory = $this->productTypeFactory->create(
                        ProductTypeFactory::TYPE_SIMPLE
                    );

                    $product = $productFactory->create($basysProduct);
                    $product = $this->modifier->apply($product, $basysProduct);

                    $callback($basysProduct, $product);
                }

                if ($this->isGroupedProduct($basysProduct)) {
                    $groups[$this->getReportSku($basysProduct)][] = $basysProduct;
                }

                $this->batchSave->add($product);
                $skusProcessed[] = $product->getSku();
            }
        }

        foreach ($groups as $report => $products) {
            try {
                $product = $this->productRepository->get($report);
            } catch (\Exception $e) {
                $productFactory = $this->productTypeFactory->create(
                    ProductTypeFactory::TYPE_GROUPED
                );

                $product = $this->modifier->apply($productFactory->create($products[0]), $products[0]);
            }

            $groupedProductLinks = [];

            /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $child */
            foreach ($products as $child) {
                $link = $this->productLinkFactory->create();

                $link->setSku($child->getSku())
                    ->setLinkType('associated')
                    ->setLinkedProductSku($product->getSku())
                    ->setLinkedProductType($product->getTypeId())
                    ->getExtensionAttributes()
                    ->setQty(0);

                $groupedProductLinks[] = $link;
            }

            $product->setProductLinks(
                $groupedProductLinks
            );

            // wierd issue with transaction importing. This value must be set manually. 
            // todo: investigate later
            $product->setTaxClassId(2);

            if (count($groupedProductLinks) >= 1) {
                $this->batchSave->add($product);
            }
        }

        $this->batchSave->save();
    }

    private function getReportSku(BasysProductInterface $product)
    {
        return $this->productMetadata->getSku($product);
    }

    private function isGroupedProduct(BasysProductInterface $product)
    {
        return $product->getReportTitle() != $product->getTitle();
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     * @throws \DomainException
     */
    private function getConnection()
    {
        if (!$this->resourceConnection) {
            $this->resourceConnection = $this->resourceConnectionFactory->create();
        }

        return $this->resourceConnection->getConnection();
    }

    private function getExistingProducts($productSkus)
    {
        $existing = $this->getConnection()->select()
            ->from(
                $this->getConnection()->getTableName('catalog_product_entity'),
                ['row_id', 'sku']
            )
            ->where(
                'sku IN (?)',
                $productSkus
            );

        $found = [];

        foreach ($this->getConnection()->fetchAll($existing) as $row) {
            $found[$row['row_id']] = $row['sku'];
        }

        return array_intersect($found, $productSkus);
    }
}
