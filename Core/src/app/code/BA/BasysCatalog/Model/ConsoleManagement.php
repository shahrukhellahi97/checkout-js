<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\ConsoleManagementInterface;
use BA\BasysCatalog\Import\CatalogImportInterface;
use BA\BasysCatalog\Model\CatalogFactory;
use BA\BasysCatalog\Model\ResourceModel\CatalogFactory as CatalogResourceFactory;
use Magento\Framework\App\ResourceConnection;
use BA\BasysCatalog\Import\Queue\QueueInterface;
use BA\BasysCatalog\Import\Queue\QueueListResultInterfaceFactory;
use BA\BasysCatalog\Import\Queue\QueueProcessorInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;

class ConsoleManagement implements ConsoleManagementInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \BA\BasysCatalog\Import\Queue\QueueProcessorInterface
     */
    protected $queueProcessor;

    /**
     * @var \BA\BasysCatalog\Import\Queue\QueueInterface
     */
    protected $queue;

    /**
     * @var \BA\BasysCatalog\Import\Queue\QueueListResultInterfaceFactory
     */
    protected $queueListResultFactory;
    
    /**
     * @var \BA\BasysCatalog\Model\CatalogFactory
     */
    protected $catalogFactory;

    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\CatalogFactory
     */
    protected $catalogResourceFactory;

    /**
     * @var \BA\BasysCatalog\Import\BasysImportInterface
     */
    protected $importer;

    private $isAreacodeSet = false;

    public function __construct(
        ResourceConnection $resourceConnection,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        QueueInterface $queue,
        QueueProcessorInterface $queueProcessor,
        FilterBuilder $filterBuilder,
        State $state,
        QueueListResultInterfaceFactory $queueListResultFactory,
        CatalogResourceFactory $catalogResourceFactory,
        CatalogImportInterface $importer,
        CatalogFactory $catalogFactory
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->queue = $queue;
        $this->queueProcessor = $queueProcessor;
        $this->state = $state;
        $this->queueListResultFactory = $queueListResultFactory;
        $this->catalogResourceFactory = $catalogResourceFactory;
        $this->catalogFactory = $catalogFactory;
        $this->importer = $importer;
    }

    public function setAreaCode()
    {
        if ($this->isAreacodeSet == false) {
            $this->state->setAreaCode(Area::AREA_ADMINHTML);
            $this->isAreacodeSet = true;
        }
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function clean()
    {
        // phpcs:disable
        $this->resourceConnection->getConnection()->query('
            INSERT IGNORE INTO `ba_basys_catalog_product_map` (`entity_id`, `basys_id`, `division_id`) 
            SELECT
                ent.entity_id,
                prd.basys_id,
                prd.division_id
            FROM catalog_product_entity ent
            LEFT JOIN ba_basys_catalog_product_map map ON map.entity_id = ent.entity_id
            INNER JOIN ba_basys_catalog_product prd ON prd.sku = ent.sku
            WHERE map.basys_id is NULL;
        ');

        $this->resourceConnection->getConnection()->query('
            DELETE t1 FROM ba_basys_catalog_product t1
            LEFT JOIN ba_basys_catalog_product_map t2 ON t1.basys_id = t2.basys_id
            WHERE t2.entity_id IS NULL
        ');

        $this->resourceConnection->getConnection()->query('
            DELETE t1 FROM ba_basys_catalog_product_map t1
            LEFT JOIN ba_basys_catalog_product t2 ON t1.basys_id = t2.basys_id
            WHERE t2.basys_id IS NULL
        ');

        $this->resourceConnection->getConnection()->query('
            UPDATE `catalog_product_entity_int` t1
            INNER JOIN eav_attribute t2 ON 
                t1.attribute_id = t2.attribute_id AND 
                t2.entity_type_id = 4 AND
                t2.attribute_code = \'status\'
            LEFT JOIN catalog_product_entity t3 ON t1.row_id = t3.row_id
            LEFT JOIN ba_basys_catalog_product_map t4 ON t4.entity_id = t3.entity_id
            SET t1.value = 2
            WHERE t4.entity_id IS NULL
        ');
    }

    public function queue($divisionId, $catalogId = null)
    {
        $this->setAreaCode();

        /** @var  \BA\BasysCatalog\Model\ResourceModel\Catalog $catalog */
        $catalogResource = $this->catalogResourceFactory->create();
        $catalogs = [];

        if ($catalogId == null) {
            $catalogs = $catalogResource->getCatalogsForDivisionId($divisionId);
        } else {
            $catalog = $this->catalogFactory->create();
            $catalogResource->load($catalog, $catalogId);

            $catalogs[] = $catalog;
        }

        /** @var \BA\BasysCatalog\Model\Catalog $catalog */
        foreach ($catalogs as $catalog) {
            $this->output->write(implode(' ', [
                'Importing Catalog',
                $catalog->getName(),
                '- '
            ]));

            try {
                $this->importer->import($catalog->getDivisionId(), $catalog->getId());
                
                $this->output->writeln('<info>success</info>');
            } catch (\Exception $e) {
                $this->output->writeln('<error>failed</error>');
                $this->output->writeln($e->getMessage());
            }
        }
    }

    public function process($divisionId, $catalogId = null, $size = 50)
    {
        // $catalogId  = $input->getOption('catalog');
        // $divisionId = $input->getOption('division');

        $this->setAreaCode();

        if ($catalogId != null) {
            $this->searchCriteriaBuilder->addFilter('catalog_id', max(0, $catalogId));
        }

        if ($divisionId != null) {
            $this->searchCriteriaBuilder->addFilter('division_id', max(0, $divisionId));
        }

        $listResult = $this->queue->list($this->searchCriteriaBuilder->create());

        if ($listResult->getSize() >= 1) {
            /** @var  \BA\BasysCatalog\Api\Data\BasysProductInterface $product */
            // foreach ($listResult->getAll() as $product) {
            //     $this->queue->setStatus($product, QueueInterface::PROCESSING);
            // }

            $this->output->writeln("{$listResult->getSize()} items in queue\n");

            // $this->queue->clean();

            $progress = new ProgressBar($this->output, $listResult->getSize());
            $progress->setFormat("%message%\n%current%/%max%[%bar%] %percent:3s%%");
            $progress->setMessage('');

            $this->queueProcessor->process($listResult, function ($basysProduct) use ($progress) {
                $progress->advance();
                $progress->setMessage("Processing <info>{$basysProduct->getSku()}</info>");

                $this->queue->setStatus($basysProduct, QueueInterface::PROCESSED);
            });

            $progress->finish();

            $this->output->writeln("\nComplete");
            $this->output->writeln("\nPlease run");
            $this->output->writeln("  <info>bin/magento ba:catalog:clean</info>");

            $this->queue->clean();
        } else {
            $this->output->writeln('<info>No items in queue</info>');
        }
    }
}