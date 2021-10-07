<?php
namespace BA\BasysCatalog\App\Http;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Framework\App\Http\Context as HttpContext;

class Context
{
    const CONTEXT_KEY = 'basys';

    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    public function __construct(
        BasysStoreManagementInterface $basysStoreManagement
    ) {
        $this->basysStoreManagement = $basysStoreManagement;
    }

    public function beforeGetVaryString(HttpContext $subject)
    {
        if ($catalog = $this->basysStoreManagement->getActiveCatalog()) {
            $subject->setValue(
                self::CONTEXT_KEY,
                $catalog->getId(),
                0
            );
        }

        return [];
    }
}