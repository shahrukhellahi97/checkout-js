<?php
namespace BA\Slider\Block\Adminhtml\Request\Grid\Renderer;

class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_storeManager;
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if ($this->_getValue($row)!='') {
            $imageUrl = $mediaDirectory . $this->_getValue($row);
            $img='<img src="' . $imageUrl . '" width="100" height="100"/>';
        } else {
            $img='No Image Uploaded';
        }
        return $img;
    }
}
