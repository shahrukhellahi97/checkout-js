<?php
namespace BA\Badges\Block;

use Magento\Framework\View\Element\Template\Context;
use BA\Badges\Helper\Data;

class DynamicCss extends \Magento\Framework\View\Element\Template
{

    protected $BadgesHelper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \BA\Badges\Helper\Data
     * @param array $data
     */
    public function __construct(Context $context, Data $BadgesHelper, array $data = [])
    {
        $this->BadgesHelper = $BadgesHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getNewLabel()
    {
        return $this->BadgesHelper->newLabel();
    }

    /**
     * @return string
     */
    public function getNewStartColor()
    {
        return $this->BadgesHelper->newStartColor();
    }

    /**
     * @return string
     */
    public function getNewEndColor()
    {
        return $this->BadgesHelper->newEndColor();
    }

    /**
     * @return string
     */
    public function getSaleLabel()
    {
        return $this->BadgesHelper->saleLabel();
    }

    /**
     * @return string
     */
    public function getSaleStartColor()
    {
        return $this->BadgesHelper->saleStartColor();
    }

    /**
     * @return string
     */
    public function getSaleEndColor()
    {
        return $this->BadgesHelper->saleEndColor();
    }
}
