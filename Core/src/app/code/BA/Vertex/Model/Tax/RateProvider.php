<?php
namespace BA\Vertex\Model\Tax;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysCatalog\Api\ProductResolverInterface;
use BA\BasysCatalog\Api\QuoteManagementInterface;
use BA\Vertex\Api\RateProviderInterface;
use BA\Vertex\Helper\Data as VertexHelper;
use BA\Vertex\Model\Request\Builder\CalculateTaxRequestInterface;
use BA\Vertex\Model\RateFactory;
use BA\Vertex\Model\ResourceModel\RateFactory as RateResourceFactory;
use Magento\Quote\Model\Quote;

class RateProvider implements RateProviderInterface
{
    /**
     * @var \BA\Vertex\Model\Request\Builder\CalculateTaxRequestInterface
     */
    protected $requestBuilder;

    /**
     * @var \BA\Vertex\Model\ResourceModel\RateFactory
     */
    protected $rateResourceFactory;

    /**
     * @var \BA\Vertex\Model\RateFactory
     */
    protected $rateFactory;

    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    /**
     * @var \BA\BasysCatalog\Api\ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var \BA\BasysCatalog\Api\QuoteManagementInterface
     */
    protected $quoteManagement;

    /**
     * @var \BA\Vertex\Helper\Data
     */
    protected $vertexHelper;

    public function __construct(
        CalculateTaxRequestInterface $requestBuilder,
        RateResourceFactory $rateResourceFactory,
        RateFactory $rateFactory, 
        ProductResolverInterface $productResolver,
        CommandPoolInterface $commandPool,
        QuoteManagementInterface $quoteManagement,
        VertexHelper $vertexHelper
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->productResolver = $productResolver;
        $this->rateResourceFactory = $rateResourceFactory;
        $this->rateFactory = $rateFactory;
        $this->commandPool = $commandPool;
        $this->quoteManagement = $quoteManagement;
        $this->vertexHelper = $vertexHelper;
    }

    public function getRates(Quote $quote)
    {
        // $request = $this->requestBuilder->build($quote);
        $rateResource = $this->rateResourceFactory->create();

        $products = $this->quoteManagement->getAllProducts($quote);
        $rates = [];

        $destinationId = 'GB';
        $sourceId = 'GB';

        $rates = $rateResource->getRatesBatch($destinationId, $sourceId, $products);

        if (count($rates) != count($products)) {
            $request = $this->requestBuilder->build($quote);
            $command = $this->commandPool->get('calculate_tax_v_async');
            
            $command->execute($request, [
                'quote_id' => $quote->getId(),
                'destination_id' => $destinationId
            ]);

            $knownIds = [];

            /** @var \BA\Vertex\Model\Rate[] $returnRates */
            $returnRates = [];

            /** @var \BA\Vertex\Model\Rate $rate */
            foreach ($rates as $rate) {
                $returnRates[$rate->getBasysId()] = $rate;
            }

            /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
            foreach ($products as $product) {
                $basysId = $this->productResolver->get($product)->getBasysId();

                // Create temporary rate
                if (!isset($returnRates[$basysId])) {
                    /** @var \BA\Vertex\Model\Rate $temporaryRate */
                    $temporaryRate = $this->rateFactory->create();

                    $temporaryRate->setBasysId($basysId)
                        ->setDestinationCountryId($destinationId)
                        ->setProductSourceCountryId($sourceId)
                        ->setSku($product->getSku())
                        ->setRate($this->vertexHelper->getDefaultRate());

                    $returnRates[] = $temporaryRate;
                }
            }
            
            return $returnRates;
        }

        return $rates;
    }
}
