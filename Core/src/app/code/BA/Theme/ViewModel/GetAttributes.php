<?php
namespace BA\Theme\ViewModel;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Pricing\Helper\Data;
use Psr\Log\LoggerInterface;

class GetAttributes implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $groupCollection;
    protected $logger;
    protected $stockItemRepository;
    protected $priceHelper;

    private $attrsArray = [];

    public function __construct(
        CollectionFactory $groupCollection,
        LoggerInterface $logger,
        StockItemRepository $stockItemRepository,
        Data $priceHelper
    ) {
        $this->groupCollection = $groupCollection;
        $this->stockItemRepository = $stockItemRepository;
        $this->logger = $logger;
        $this->priceHelper = $priceHelper;
    }
    /**
     * Retrive attributes from each associated products
     * @param int $groupId
     * @param object $item
     * @return void
     */
    public function createAttributeArray($groupId, $item)
    {
        try {
            $productAttributes = $item->getAttributes($groupId);
            $productStock = $this->stockItemRepository->get($item->getId());
            foreach ($productAttributes as $attribute) {
                $attrCode = $attribute->getAttributeCode();
                $attrValue = $item->getResource()->getAttributeRawValue($item->getId(), $attrCode, $item->getId());
                /* Checking for blank values */
                $finalValue = is_array($attrValue) ? '' : $attrValue;
                $this->attrsArray[$attrCode][$item->getId()] = $finalValue;
            }
            $this->attrsArray['stock'][$item->getId()] = $productStock->getQty();
            
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
    
    /**
     * Get the associated products from the phtml
     * and create the attributes of each product
     * @param array $items
     * @return array
     */
    public function getLayoutCustAttributes($items)
    {
        try {
            $groupCollection = $this->groupCollection->create();
            $groupCollection->addFieldToFilter('attribute_group_name', 'Layout Customizations');
            $groupId = $groupCollection->getFirstItem()->getData('attribute_group_id');
            foreach ($items as $item) {
                $this->createAttributeArray($groupId, $item);
            }
            $this->filterEmptyAttributes();
            return $this->attrsArray;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
    /**
     * Drop the attributes if all the values set null
     * @return void
     */
    private function filterEmptyAttributes()
    {
        foreach ($this->attrsArray as $key => $innerArray) { //check for each element
            foreach ($innerArray as $innerValue) {
                if (!empty($innerValue)) {
                    continue 2;//stop investigating at first non empty, we shoud keep this
                }
            }
            //all values in innerArray are empty, drop this
            unset($this->attrsArray[$key]);
        }
    }
}
