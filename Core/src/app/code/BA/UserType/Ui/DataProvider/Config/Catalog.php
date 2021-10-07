<?php
namespace BA\UserType\Ui\DataProvider\Config;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use BA\UserType\Model\Config\ResourceModel\Catalog\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class Catalog extends AbstractDataProvider
{
    /**
     * @var \BA\UserType\Model\Config\ResourceModel\Catalog\Collection
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        BasysStoreManagementInterface $basysStoreManagement,
        RequestInterface $request,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->basysStoreManagement = $basysStoreManagement;
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        // $this->basysStoreManagement = $basysStoreManagement;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    protected function prepareUpdateUrl()
    {
        $id = $this->request->getParam('id');

        $this->meta = array_replace_recursive(
            $this->meta,
            [
                'testInsertListing' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'render_url' => $this->urlBuilder
                                    ->getUrl('mui/index/render/id/' . $id),
                                'update_url' => $this->urlBuilder->getUrl('mui/index/render/id/' . $id)
                            ]
                        ],
                    ]
                ]
            ]
        );
    }

    public function getData()
    {
        $request = $this->request->getParams();

        $websiteId = $this->request->getParam('website_id');
        $configId  = $this->request->getParam('config_id');
        $result = [];

        if ($websiteId != null && $configId != null) {
            $catalogs = $this->basysStoreManagement->getActiveCatalogs($websiteId);

            $known = $this->collection->addFieldToFilter('config_id', $configId);
            $known->load();

            $active = [];

            /** @var \BA\UserType\Model\Config\Catalog $cfg */
            foreach ($known as $cfg) {
                $active[$cfg->getCatalogId()] = (int) $cfg->getIsActive();
            }

            /** @var \BA\BasysCatalog\Api\Data\CatalogInterface $catalog */
            foreach ($catalogs as $catalog) {
                $result[] = [
                    'catalog_id' => (int) $catalog->getId(),
                    'name'       => $catalog->getName(),
                    'currency'   => $catalog->getCurrency(),
                    'is_active'  => isset($active[$catalog->getId()]) ? $active[$cfg->getCatalogId()] : 0
                ];
            }
        }

        return [
            'items' => $result,
            'totalRecords' => count($result)
        ];
    }

    // public function getData()
    // {
    //     if (isset($this->loadedData)) {
    //         return $this->loadedData;
    //     }
        
    //     $items = $this->collection->getItems();

    //     /** @var \BA\UserType\Model\Config $model */
    //     foreach ($items as $model) {
    //         $this->loadedData[$model->getId()] = $model->getData();
    //     }

    //     $data = $this->dataPersistor->get('ba_usertype_config_catalog');
        
    //     if (!empty($data)) {
    //         $model = $this->collection->getNewEmptyItem();
    //         $model->setData($data);

    //         $this->loadedData[$model->getId()] = $model->getData();
    //         $this->dataPersistor->clear('ba_usertype_config_catalog');
    //     }
        
    //     return $this->loadedData;
    // }
}