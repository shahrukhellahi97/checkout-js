<?php
namespace BA\Punchout\Processor;

use BA\Punchout\Api\Data\DTOs\Request\OrderMessageInterface;
use BA\Punchout\Api\Processor\OrderMessageProcesserInterface;
use BA\Punchout\Model\CredentialType;

class OrderMessageProcesser implements OrderMessageProcesserInterface
{
    /**
     * @var int
     */
    protected $requestId;

    /**
     * @var \BA\Punchout\Model\DTOs\Request\OrderMessageFactory
     */
    protected $orderMessageFactory;

    /**
     * @var \BA\Punchout\Model\RequestRepository
     */
    protected $requestRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Quote\Model\Quote
     */
    protected $quote;

    /**
     * @var \BA\Punchout\Model\DTOs\Types\Item
     */
    protected $itemFactory;

    /**
     * @var \BA\Punchout\Helper\Session
     */
    protected $sessionHelper;

    protected $setupRequest;

    public function __construct(
        \BA\Punchout\Model\DTOs\Request\OrderMessageFactory $orderMessageFactory,
        \BA\Punchout\Model\DTOs\Types\ItemFactory $itemFactory,
        \BA\Punchout\Model\RequestRepository $requestRepository,
        \BA\Punchout\Helper\Session $sessionHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->orderMessageFactory = $orderMessageFactory;
        $this->requestRepository = $requestRepository;
        $this->itemFactory = $itemFactory;
        $this->dateTime = $dateTime;
        $this->sessionHelper = $sessionHelper;
    }

    public function setRequestId(int $id)
    {
        $this->requestId = $id;
    }

    public function addQuote(\Magento\Quote\Model\Quote $quote)
    {
        $this->quote = $quote;
    }

    public function getQuote()
    {
        return $this->sessionHelper->getQuote();
    }

    public function getSetupRequest()
    {
        if ($this->setupRequest == null) {
            $this->setupRequest = $this->requestRepository->loadById($this->requestId);
        }

        return $this->setupRequest;
    }

    /**
     * Very very ugly
     */
    public function getOrderMessage(): OrderMessageInterface
    {
        $request = $this->getSetupRequest();

        /** @var \BA\Punchout\Model\DTOs\Request\OrderMessage $orderMessage */
        $orderMessage = $this->orderMessageFactory->create();

        // $orderMessage->setPayloadId($request->getPayloadId());
        $orderMessage->setTimestamp($this->dateTime->gmtDate(\DateTime::ISO8601));

        // Manage Header
        /** @var \BA\Punchout\Api\Data\DTOs\Types\HeaderInterface $header */
        $header = $orderMessage->getHeader();

        // $header->getTo()
        //     ->setIdentity($request->getCredentialByType(CredentialType::TO)->getIdentity());

        // $header->getFrom()
        //     ->setIdentity($request->getCredentialByType(CredentialType::FROM)->getIdentity());

        $header->getSender()
            ->setIdentity('AN01000044908-T')
            ->setAttributes([
                ['key' => 'user_agent', 'value' => 'Brand Addition']
            ]);

        // Manage Payload
        /** @var \BA\Punchout\Api\Data\DTOs\Request\Body\OrderMessageBodyInterface $payload */ 
        $payload = $orderMessage->getPayload();

        $payload->setBuyerCookie($request->getBuyerCookie());
        $payload->getBrowserFromPost()->setUrl($request->getBrowserFromPost());
        $payload->getReturnUrl()->setUrl($request->getReturnUrl());

        /** @var \BA\Punchout\Api\Data\DTOs\Types\ShippingInterface $shipping */
        $shipping = $payload->getShipping();

        $shipping->setDescription('');
        $shipping->getTotal()->setValue($this->getQuote()->getShippingAddress()->getShippingAmount());
        $shipping->getTotal()->setCurrency($this->sessionHelper->getQuote()->getCurrency()->getQuoteCurrencyCode());

        // Add Address

        $address = $this->sessionHelper->getCustomer()->getDefaultShippingAddress();

        // $shipping->getShipTo()
        //     ->setDeliverTo($address->getFirstname())
        //     ->setStreet($address->getStreetFull())
        //     ->setCity($address->getCity())
        //     ->setRegion($address->getRegion())
        //     ->setCountry($address->getCountry())
        //     ->setPostalCode($address->getPostcode());

        

        // Handle Totals and Items
        $items = [];

        $payload->getTotal()->setValue($this->sessionHelper->getQuote()->getSubtotal());
        $payload->getTotal()->setCurrency($this->sessionHelper->getQuote()->getCurrency()->getQuoteCurrencyCode());

        /** @var \Magento\Quote\Api\Data\CartItemInterface $item */
        foreach ($this->sessionHelper->getQuote()->getAllVisibleItems() as $item) {
            /** @var \BA\Punchout\Model\DTOs\Types\Item $tmp */
            $tmp = $this->itemFactory->create();

            $tmp->setSupplierPartId($item->getSku());
            $tmp->setQuantity($item->getQty());
            $tmp->setDescription($item->getName());
            $tmp->getClassification()->setDomain('UNSPSC');
            $tmp->getClassification()->setValue(10000000);
            $tmp->getUnitPrice()->setValue($item->getPrice());
            $tmp->getUnitPrice()->setCurrency($this->sessionHelper->getQuote()->getCurrency()->getQuoteCurrencyCode());
            $tmp->setAttributes([
                ['key' => 'leadtime', 'value' => '4'],
                ['key' => 'productid', 'value' => '10']
            ]);

            $payload->addItem($tmp);
        }
        
        return $orderMessage;
    }
}