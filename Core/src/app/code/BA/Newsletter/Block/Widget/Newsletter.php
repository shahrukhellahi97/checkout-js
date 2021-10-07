<?php
namespace BA\Newsletter\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use BA\Badges\Helper\Data as NewsletterHelper;
use Magento\Framework\View\Element\Template\Context;

class Newsletter extends Template implements BlockInterface
{

    protected $_template = "widget/newsletter.phtml";
    protected $newsletterHelper;

    public function __construct(
        Context $context,
        NewsletterHelper $newsletterHelper,
        array $data = []
    ) {
        $this->newsletterHelper = $newsletterHelper;
        parent::__construct($context, $data);
    }
    /**
     * @return int
     */
    public function getGroupId()
    {
        return $this->newsletterHelper->getGroupId();
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->newsletterHelper->getGroupName();
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->newsletterHelper->getToken();
    }
}
