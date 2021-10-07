<?php
namespace BA\BasysOrders\Model\Request\Builder;

use BA\BasysCatalog\Api\ProductResolverInterface;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session as CheckoutSession;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Session;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Model\BasysStoreManagement;

class OrderRequest
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \BA\BasysCatalog\Api\ProductResolverInterface
     */
    protected $basysProduct;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    protected $activeCatalogs;
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $BasysStoreManagement;
    protected $configHelper;

    public function __construct(
        ProductResolverInterface $basysProduct,
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession,
        LoggerInterface $logger,
        Session $customerSession,
        BasysStoreManagementInterface $BasysStoreManagement,
        BasysStoreManagement $activeCatalogs,
        Data $configHelper
    ) {
        $this->basysProduct = $basysProduct;
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->activeCatalogs = $activeCatalogs;
        $this->BasysStoreManagement = $BasysStoreManagement;
        $this->configHelper = $configHelper;
    }

    public function build(OrderInterface $order): array
    {
        try {
            $shippingData = $order->getShippingAddress();
            $streets = $shippingData->getStreet();
            $sAddress2 = isset($streets[1]) ? $streets[1] : '';
            $sAddress3 = isset($streets[2]) ? $streets[1] : '';
            $billingData = $order->getBillingAddress();
            $bstreets = $billingData->getStreet();
            $bAddress2 = isset($bstreets[1]) ? $bstreets[1] : '';
            $bAddress3 = isset($bstreets[2]) ? $bstreets[1] : '';

            $paymentDataJson = $order->getPayment()->getAdditionalData();
            $paymentData = json_decode($paymentDataJson, JSON_OBJECT_AS_ARRAY);
          
            /* if check/money in hand*/
            $transaction_guid = isset($paymentData['transaction_guid'])? $paymentData['transaction_guid']: '00000000-0000-0000-0000-000000000000';

            if (isset($paymentData['3dsecure']['dateTime'])) {
                $dt = date_format(date_create($paymentData['3dsecure']['dateTime']), 'Y-m-d');
            } else {
                $dt = date('Y-m-d');
            }
            $ccLast = $order->getPayment()->getCcLast4();
            $ccLast = ($ccLast !='')? $ccLast:'0000';

            return [
                'Order' => [
                    'OrderHeader' => [
                        'DivisionID' => $this->BasysStoreManagement->getActiveCatalog()->getDivisionId(),
                        'KeyGroupID' => $this->BasysStoreManagement->getActiveKeyGroup()->getId(),
                        'CustomerContactID' => $this->customerSession->getBasysCustomerId(),
                        'PaymentTypeID' => 4,
                        'SourceCodeID' => $this->BasysStoreManagement->getActiveSourceCode()->getId(),
                        'OrderCurrency' => $order->getOrderCurrencyCode(),
                        'GoodsTotal' => $order->getGrandTotal(),
                        'Freight' => $order->getShippingInclTax(),
                        'Tax' => $order->getTaxAmount(),
                        'TransactionRef' => $transaction_guid,
                        'Last4Digits' => $ccLast,
                        'AuthorisationDate' => $dt,
                        'ShipTo' => [
                            'ContactName' => $shippingData->getFirstName() . ' ' . $shippingData->getLastName(),
                            'Address1' => $streets[0],
                            'Address2' => $sAddress2,
                            'Address3' => $sAddress3,
                            'City' =>  $shippingData->getCity(),
                            'PostCode' =>  $shippingData->getPostCode(),
                            'County' => $shippingData->getRegion(),
                            'Country' => $shippingData->getCountryId(),
                            'Carrier_Service_ID' => 1,
                            'Carrier_Service_Text' => $order->getShippingDescription()
                        ],
                        'BillTo' => [
                            'ContactName' => $billingData->getFirstName() . ' ' . $billingData->getLastName(),
                            'Address1' => $streets[0],
                            'Address2' => $bAddress2,
                            'Address3' => $bAddress3,
                            'City' => $billingData->getCity(),
                            'PostCode' => $billingData->getPostCode(),
                            'County' => $billingData->getRegion(),
                            'Country' => $billingData->getCountryId()
                        ],
                    ],
                    'OrderLines' => $this->addItems($order->getAllVisibleItems())
                ],
            ];
            /** This is for gift certificate */
            if ($this->checkoutSession->getEnableGiftCard()) {
                $orderRequestArr['Order']['OrderHeader']['GiftCertificates'] =  [
                     'GiftCertificate' => [
                        'GiftCertificateReference' => $order->getCertificateref(),
                        'Amount' => abs($order->getGiftAmt()) /** reduced gift amt from the sales order table */
                     ]
                    ];
            }
            return $orderRequestArr;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function addItems($items)
    {
        try {
            $orderItems = [];
            foreach ($items as $item) {
                $product = $this->productRepository->getById($item->getProductId());
                $basysProduct = $this->basysProduct->get($product);
                $orderItems[] =   [
                        'OrderLine' => [
                                'CatalogueID' => $basysProduct->getData('catalog_id'),
                                'CatalogueAlias' => $item->getSku(),
                                'UnitPrice' => $item->getPrice(),
                                'LineQty' => $item->getQtyOrdered(),
                                'ProductID' => $basysProduct->getData('basys_id'),
                                'BaseColour' => $basysProduct->getData('base_colour'),
                                'TrimColour' => $basysProduct->getData('trim_colour'),
                                'SupplierID' => 4746
                            ]
                    ];
            }
            return $orderItems;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function giftCardUsed()
    {
        try {
            $giftCertificate = [
                'GiftCertificate' => [
                    'GiftCertificateReference' =>'',
                    'Amount' => 45.90
                ]
            ];
            return $giftCertificate;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
