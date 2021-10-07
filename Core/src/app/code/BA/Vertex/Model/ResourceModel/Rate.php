<?php
namespace BA\Vertex\Model\ResourceModel;

use BA\Vertex\Api\Data\RateInterface;
use BA\Vertex\Helper\Data;
use BA\Vertex\Model\RateFactory;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Psr\Log\LoggerInterface;

class Rate extends AbstractDb
{
    /**
     * @var \BA\Vertex\Model\RateFactory
     */
    protected $rateFactory;

    /**
     * @var \BA\Vertex\Helper\Data
     */
    protected $helper;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        RateFactory $rateFactory,
        Data $helper,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->helper = $helper;
        $this->rateFactory = $rateFactory;
    }
    
    protected function _construct()
    {
        $this->_init(RateInterface::SCHEMA_NAME, RateInterface::BASYS_ID);
        $this->_isPkAutoIncrement = false;
    }

    public function setRate(
        \BA\Vertex\Model\Rate $rate
    ) {
        return $this->save($rate);
    }

    public function updateRates(array $rates)
    {
        $transaction = $this->getConnection()->beginTransaction();

        /** @var \BA\Vertex\Api\Data\RateInterface $rate */
        foreach ($rates as $rate) {
            $transaction->insertOnDuplicate(
                $this->getMainTable(),
                [
                    RateInterface::DESTINATION_COUNTRY_ID => $rate->getDestinationCountryId(),
                    RateInterface::SOURCE_COUNTRY_ID => $rate->getSourceCountryId(),
                    RateInterface::BASYS_ID => $rate->getBasysId(),
                    RateInterface::PRODUCT_SOURCE_COUNTRY_ID => $rate->getProductSourceCountryId(),
                    RateInterface::RATE => $rate->getRate()
                ]
            );
        }

        try {
            $transaction->commit();
        } catch (\Exception $e) {
            $this->_logger->error('Unabled to update rates', $rates);
        }
    }

    public function getRatesBatch(
        string $destinationCountryId,
        string $sourceCountryId,
        array $products = []
    ) {
        $select = $this->getConnection()->select()
            ->from(
                ['p' => $this->getMainTable()],
                'p.*',
            )
            ->join(
                ['m' => $this->getTable('ba_basys_catalog_product_map')],
                'm.basys_id = p.basys_id'
            )
            ->join(
                ['e' => $this->getTable('catalog_product_entity')],
                'e.entity_id = m.entity_id'
            )
            ->where(
                'm.entity_id IN (?)',
                array_map(function ($product) {
                    /** @var \Magento\Catalog\Model\Product $prodct */
                    return $product->getId();
                }, $products)
            )
            ->where(
                sprintf('p.%s = ?', RateInterface::DESTINATION_COUNTRY_ID),
                $destinationCountryId
            )
            ->where(
                sprintf('p.%s = ?', RateInterface::SOURCE_COUNTRY_ID),
                $sourceCountryId
            )
            ->where(
                sprintf('p.%s = ?', RateInterface::PRODUCT_SOURCE_COUNTRY_ID),
                'GB'
            )
            ->where(
                sprintf('p.%s > TIMESTAMP(DATE_SUB(NOW(), INTERVAL ? SECOND))', RateInterface::MODIFIED),
                $this->helper->getRateExpiryDays()
            );

        $result = [];

        $x = $select->__toString();

        if ($rows = $this->getConnection()->fetchAll($select)) {
            foreach ($rows as $row) {
                /** @var \BA\Vertex\Model\Rate $rate */
                $rate = $this->rateFactory->create();
                $result[] = $rate->setData($row);
            }
        }

        return $result;
    }

    public function getRate(
        string $destinationCountryId,
        string $sourceCountryId,
        \Magento\Catalog\Model\Product $product
    ) {
        $select = $this->getConnection()->select()
            ->from(
                ['p' => $this->getMainTable()],
                'p.*'
            )
            ->join(
                ['m' => $this->getTable('ba_basys_catalog_product_map')],
                'm.basys_id = p.basys_id'
            )
            ->join(
                ['e' => $this->getTable('catalog_product_entity')],
                'e.entity_id = m.entity_id'
            )
            ->where(
                'm.entity_id = ?',
                $product->getId()
            )
            ->where(
                sprintf('p.%s = ?', RateInterface::DESTINATION_COUNTRY_ID),
                $destinationCountryId
            )
            ->where(
                sprintf('p.%s = ?', RateInterface::SOURCE_COUNTRY_ID),
                $sourceCountryId
            )
            ->where(
                sprintf('p.%s = ?', RateInterface::PRODUCT_SOURCE_COUNTRY_ID),
                'GB'
            )
            ->where(
                sprintf('p.%s > TIMESTAMP(DATE_SUB(NOW(), INTERVAL ? SECOND))', RateInterface::MODIFIED),
                $this->helper->getRateExpiryDays()
            )
            ->limit(1);
    
        if ($data = $this->getConnection()->fetchRow($select)) {
            /** @var \BA\Vertex\Model\Rate $rate */
            $rate = $this->rateFactory->create();

            return $rate->setData($data);
        }

        return null;
    }
}