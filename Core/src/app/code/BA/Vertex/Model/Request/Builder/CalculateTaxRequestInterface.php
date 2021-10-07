<?php
namespace BA\Vertex\Model\Request\Builder;

use Magento\Quote\Model\Quote;

interface CalculateTaxRequestInterface
{
    /**
     * Build request for tax call
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @return array
     */
    public function build(Quote $quote);
}
