<?php
namespace BA\Slider\Block\Adminhtml\Request;

use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Helper\Data;
use Magento\Store\Model\WebsiteFactory;
use BA\Slider\Model\ResourceModel\Request\Collection as SliderCollection;
use Magento\Framework\Module\Manager;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
    protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        WebsiteFactory $websiteFactory,
        SliderCollection $collectionFactory,
        Manager $moduleManager,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);

    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * Get all websites
     */
    public function getWebsites()
    {
       $websitesArr = [];
       $websites = $this->_storeManager->getWebsites();
       foreach($websites as $website) {
            $websitesArr[$website->getId()] = $website->getName();
       }
       return $websitesArr;
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        try {
                $collection =$this->_collectionFactory->load();
                $this->setCollection($collection);
                parent::_prepareCollection();
                return $this;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => 'title'
            ]
        );
        $this->addColumn(
            'image',
            [
                'header' => __('Slider Images'),
                'index' => 'image',
                'class' => 'image',
                'renderer'  => '\BA\Slider\Block\Adminhtml\Request\Grid\Renderer\Image',
            ]
        );
        $this->addColumn(
            'website_id',
            [
                'header' => __('Websites'),
                'index' => 'website_id',
                'class' => 'website',
                'renderer'  => '\BA\Slider\Block\Adminhtml\Request\Grid\Renderer\Displayname',
                'type' => 'options',
                'options' => $this->getWebsites(),
                'filter_condition_callback' => [$this, '_websiteFilter'],
            ]
        );
        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index' => 'sort_order',
                'class' => 'sort_order'
            ]
        );
        $this->addColumn(
            'enabled',
            [
                'header' => __('Enabled'),
                'index' => 'enabled',
                'class' => 'enabled',
                'renderer'  => '\BA\Slider\Block\Adminhtml\Request\Grid\Renderer\Enabled',
                'type' => 'options',
                'options' => ['1'=>'Yes','2'=>'No'],
                'filter_condition_callback' => [$this, '_enabledFilter'],
            ]
        );
        $this->addColumn(
            'created',
            [
                'header' => __('Created'),
                'index' => 'created',
                'type' => 'date',
            ]
        );
        /*{{CedAddGridColumn}}*/

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    protected function _websiteFilter($collection, $column)
    {
        $websiteId = $column->getFilter()->getValue();
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->addFieldToFilter('website_id', ['eq' => $websiteId]);
    }

    protected function _enabledFilter($collection, $column)
    {
        $enabled = $column->getFilter()->getValue();
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->addFieldToFilter('enabled', ['eq' => $enabled]);
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('slider/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('slider/*/index', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'slider/*/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
