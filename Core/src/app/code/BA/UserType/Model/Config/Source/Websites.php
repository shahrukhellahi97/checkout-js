<?php
namespace BA\UserType\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManager;

class Websites implements OptionSourceInterface
{
    /**
     * @var \Magento\Store\Model\StoreManager
     */
    protected $storeManager;

    public function __construct(
        StoreManager $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    public function toOptionArray()
    {
        $result = [];

        /** @var \Magento\Store\Api\Data\WebsiteInterface $website */
        foreach ($this->storeManager->getWebsites() as $website) {
            $result[] = [
                'label' => $website->getName(),
                'value' => $website->getId(),
            ];
        }

        return $result;
    }
    
}