<?php
namespace BA\BasysCatalog\Controller\Adminhtml\Index;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\ResourceModel\Catalog\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;

class Index extends Action implements HttpPostActionInterface
{
    protected $logger;
    protected $resultJsonFactory;
    protected $catalogCollectionFactory;
    protected $configInterface;
    protected $cacheTypeList;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger,
        CollectionFactory $catalogCollectionFactory,
        WriterInterface $configInterface,
        TypeListInterface $cacheTypeList
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->catalogCollectionFactory = $catalogCollectionFactory;
        $this->configInterface = $configInterface;
        $this->cacheTypeList = $cacheTypeList;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $divisionId = (int) $this->_request->getParam('divisionId');
            $collection = $this->catalogCollectionFactory->create();
            $catalogs = $collection->addFieldToFilter(CatalogInterface::DIVISION_ID, $divisionId)->load();
            $result = [];

            /** @var BA\BasysCatalog\Model\Catalog $catalogs */
            foreach ($catalogs as $catalog) {
                $result[] = [
                    'label' => sprintf("%s ( ID: %s )", $catalog->getName(), $catalog->getId()),
                    'value' => (int) $catalog->getId(),
                ];
            }

            // $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);

            /** @var \Magento\Framework\Controller\Result\Json $result */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($result);
            return $resultJson;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
