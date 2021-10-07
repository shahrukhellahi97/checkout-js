<?php
namespace BA\Vertex\Observer;

use BA\Vertex\Api\RateProviderInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\QuoteRepository;
use Psr\Log\LoggerInterface;

class QuoteCollectTotalsAfter implements ObserverInterface
{
    /**
     * @var int
     */
    protected $called = 0;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \BA\Vertex\Api\RateProviderInterface
     */
    protected $taxCalculator;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageInterface;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    public function __construct(
        LoggerInterface $logger,
        RateProviderInterface $taxCalculator,
        ManagerInterface $messageInterface,
        QuoteRepository $quoteRepository
    ) {
        $this->logger = $logger;
        $this->taxCalculator = $taxCalculator;
        $this->messageInterface = $messageInterface;
        $this->quoteRepository = $quoteRepository;
    }

    public function execute(Observer $observer)
    {
        // if ($this->called == 0) {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getData('quote');
        
        $x = $this->taxCalculator->getRates($quote);

        return $this;
    }
}
