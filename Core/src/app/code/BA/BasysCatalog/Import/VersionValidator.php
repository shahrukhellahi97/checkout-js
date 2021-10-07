<?php
namespace BA\BasysCatalog\Import;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\Data\ChecksumInterface;
use BA\BasysCatalog\Model\ResourceModel\BasysProductFactory as BasysProductResourceFactory;

class VersionValidator implements VersionValidationInterface
{
    /**
     * @var array
     */
    protected $catalogs;

    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\BasysProductResourceFactory
     */
    protected $basysProductResourceFactory;

    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\BasysProduct
     */
    protected $basysProductResource;

    /**
     * @var int[]|array
     */
    protected $catalogIds;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    public function __construct(
        BasysProductResourceFactory $basysProductResourceFactory
    ) {
        $this->basysProductResourceFactory = $basysProductResourceFactory;
    }

    public function valid(BasysProductInterface $product)
    {   
        $version = $product->getVersion();

        if ($product instanceof ChecksumInterface) {
            $version = $product->getChecksum();
        }

        $dbVersion = $this->getDatabaseVersion($product);

        return $dbVersion != null && $version == $dbVersion;
    }

    private function getAssociatedCatalogIds(BasysProductInterface $product)
    {
        if ($this->catalogIds == null) {
            $connection = $this->getResource()->getConnection();

            $select = $connection->select()
                ->from(
                    ['prod' => $this->getResource()->getMainTable()],
                    [new \Zend_Db_Expr("DISTINCT prod.catalog_id")]
                )
                ->where(
                    'prod.division_id = ?',
                    $product->getDivisionId()
                );

            foreach ($connection->fetchAll($select) as $row) {
                $this->catalogIds[] = $row['catalog_id'];
            }
        }

        return $this->catalogIds;
    }

    /**
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $product
     * @return int|null
     * @throws \DomainException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Db_Select_Exception
     */
    private function getDatabaseVersion(BasysProductInterface $product)
    {
        if ($this->catalogs == null) {
            $catalogIds = $this->getAssociatedCatalogIds($product);

            $connection = $this->getResource()->getConnection();
            $expression = 'version';

            if ($product instanceof ChecksumInterface) {
                $expression = new \Zend_Db_Expr(
                    sprintf(
                        "CRC32(CONCAT(%s)) as new_version",
                        implode(', ', array_map(function ($column) {
                            return 'IFNULL(p.' . $column . ', \'\')';
                        }, $product->getChecksumColumnNames()))
                    )
                );
            }

            $select = $connection->select()
                ->from(
                    ['p' => $this->getResource()->getMainTable()],
                    array_merge(
                        ['p.catalog_id', 'p.basys_id'],
                        [$expression],
                    )
                )
                ->where(
                    'p.catalog_id IN (?)',
                    [$catalogIds]
                );

            foreach ($connection->fetchAll($select) as $row) {
                if (!isset($this->catalogs[$row['catalog_id']])) {
                    $this->catalogs[$row['catalog_id']] = [];
                }

                $this->catalogs[$row['catalog_id']][$row['basys_id']] = $row['new_version'];
            }
        }

        try {
            return $this->catalogs[$product->getCatalogId()][$product->getBasysId()];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function set($catalogId, $productId, $version)
    {
        $connection = $this->getResource()->getConnection();

        $transaction = $connection->beginTransaction();

        $transaction->update(
            $this->getResource()->getMainTable(),
            ['version' => $version],
            [
                'catalog_id = ?' => $catalogId,
                'product_id = ?' => $productId
            ]
        );

        try {
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
    }

    /**
     * @return \BA\BasysCatalog\Model\ResourceModel\BasysProduct
     */
    private function getResource()
    {
        if ($this->basysProductResource == null) {
            $this->basysProductResource = $this->basysProductResourceFactory->create();
        }

        return $this->basysProductResource;
    }
}
