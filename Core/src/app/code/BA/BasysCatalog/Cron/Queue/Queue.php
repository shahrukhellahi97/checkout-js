<?php
namespace BA\BasysCatalog\Cron\Queue;

use BA\BasysCatalog\Cron\JobInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Import\AsyncCatalogImport;
use BA\BasysCatalog\Import\CatalogImportInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\MessageQueue\LockInterface;
use Magento\Store\Api\StoreManagementInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Queue implements JobInterface
{
    /**
     * How many catalogs to queue each time
     */
    const BATCH_SIZE = 5;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $catalogHelper;

    /**
     * @var \BA\BasysCatalog\Import\CatalogImportInterface
     */
    protected $catalogImport;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var array
     */
    protected $catalogs = [];

    public function __construct(
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        AsyncCatalogImport $catalogImport,
        ResourceConnection $resourceConnection,
        Data $catalogHelper
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->catalogImport = $catalogImport;
        $this->resourceConnection = $resourceConnection;
        $this->catalogHelper = $catalogHelper;
    }

    public function execute()
    {
        $divisions = [];

        /** @var \Magento\Store\Api\Data\WebsiteInterface $website */
        foreach ($this->storeManager->getWebsites() as $website) {
            $divisionId = $this->catalogHelper->getDivisionId(
                $website->getId()
            );

            if ($divisionId != null) {
                $catalogs = $this->catalogHelper->getActiveCatalogIds(
                    $website->getId()
                );

                $expiration = $this->catalogHelper->getCatalogueExpiration();

                // $catalogs = array_filter($catalogs, function ($id) use ($expiration) {
                //     return ( time() - $expiration ) >  $this->getCatalogUpdatedAt($id);
                // });

                if (!isset($divisions[$divisionId])) {
                    $divisions[$divisionId] = $catalogs;
                } else {
                    $divisions[$divisionId] = array_merge(
                        $divisions[$divisionId],
                        $catalogs
                    );
                }
            }
        }

        $count = 0;

        foreach ($divisions as $divisionId => $catalogs) {
            $this->logger->info(
                'Updating Catalog', 
                [
                    'division' => $divisionId,
                    'catalogs' => $catalogs
                ]
            );

            foreach ($catalogs as $catalogId) {
                $this->catalogImport->import($divisionId, $catalogId);
                $this->resourceConnection->getConnection()->update(
                    'ba_basys_store_catalog',
                    [
                        'updated_at' => new \Zend_Db_Expr('NOW()')
                    ],
                    'catalog_id = ' . (int) $catalogId
                );

                $count += 1;
            }
        }

        
        $this->logger->info(
            'Updating Catalog', [
                'total' => $count
            ]
        );
    }

    private function getCatalogUpdatedAt($catalogId)
    {
        if (!$this->catalogs) {
            $connection = $this->resourceConnection->getConnection();

            $select = $connection->select()
                ->from(
                    $connection->getTableName('ba_basys_store_catalog'),
                    ['catalog_id', new \Zend_Db_Expr('UNIX_TIMESTAMP(updated_at) as `timestamp`')]
                );

            if ($rows = $connection->fetchAll($select)) {
                foreach ($rows as $row) {
                    $this->catalogs[$row['catalog_id']] = (int) $row['timestamp'];
                }
            }
        }

        return (int) $this->catalogs[$catalogId];
    }
}