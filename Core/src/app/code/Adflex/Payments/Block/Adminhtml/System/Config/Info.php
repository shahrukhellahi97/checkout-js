<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\ResourceInterface;

/**
 * Class Info
 *
 * @package Adflex\Payments\Block\Adminhtml\System\Config
 */
class Info extends Field
{
    /**
     * Template path
     *
     * @var string
     */
    protected $_template = 'system/config/info.phtml';

    /**
     * @var \Magento\Framework\Module\ResourceInterface
     */
    protected $_moduleResource;

    /**
     * Info constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Module\ResourceInterface $moduleResource
     * @param array $data
     */
    public function __construct(
        Context $context,
        ResourceInterface $moduleResource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_moduleResource = $moduleResource;
    }

    /**
     * Render fieldset html
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $columns = $this->getRequest()->getParam('website') || $this->getRequest()->getParam('store') ? 5 : 4;
        return $this->_decorateRowHtml($element, "<td colspan='{$columns}'>" . $this->toHtml() . '</td>');
    }

    /**
     * @return false|string
     */
    public function getModuleVersion()
    {
        return $this->_moduleResource->getDbVersion('Adflex_Payments');
    }

    /**
     * @param $path
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig($path)
    {
        return $this->_scopeConfig->getValue($path);
    }
}
