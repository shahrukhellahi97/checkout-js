<?php
namespace BA\BasysCatalog\App\PageCache;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ObjectManager;

class Identifier extends \Magento\Framework\App\PageCache\Identifier
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json|null
     */
    protected $newSerializer;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\Http\Context $context,
        BasysStoreManagementInterface $basysStoreManagement,
        Json $serializer = null
    ) {
        parent::__construct($request, $context, $serializer);

        // Not sure why the serializer is private in the parent class?
        $this->newSerializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
        $this->basysStoreManagement = $basysStoreManagement;
    }

    public function getValue()
    {
        $data = [
            $this->request->isSecure(),
            $this->request->getUriString(),
            $this->request->get(\Magento\Framework\App\Response\Http::COOKIE_VARY_STRING)
                ?: $this->context->getVaryString()
        ];

        if ($catalog = $this->basysStoreManagement->getActiveCatalog()) {
            $data[] = $catalog->getId();
            return sha1($this->newSerializer->serialize($data));
        } else {
            return parent::getValue();
        }
    }
}