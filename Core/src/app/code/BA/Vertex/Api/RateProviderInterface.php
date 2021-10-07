<?php
namespace BA\Vertex\Api;

use Magento\Quote\Model\Quote;
use Magento\Tax\Api\Data\QuoteDetailsInterface;

interface RateProviderInterface
{
    /**
     * Calculate tax from vertex webservice
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @throws \BA\Basys\Exception\BasysException
     * @return \BA\Vertex\Api\Data\RateInterface[]|array|null
     */
    public function getRates(Quote $quote);
}
