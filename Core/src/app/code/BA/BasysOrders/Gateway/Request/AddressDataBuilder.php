<?php
namespace BA\BasysOrders\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class AddressDataBuilder implements BuilderInterface
{
    /**
     * @var \BA\Freight\Helper\Data
     */
    protected $freightHelper;

    /**
     * @var \BA\Freight\Model\Config\Source\Carrier
     */
    protected $carrierSource;

    public function __construct(
        \BA\Freight\Helper\Data $freightHelper,
        \BA\Freight\Model\Config\Source\Carrier $carrierSource
    ) {
        $this->freightHelper = $freightHelper;
        $this->carrierSource = $carrierSource;
    }

    public function build(array $buildSubject)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $buildSubject['payment']->getPayment()->getOrder();

        // $x = $order->getShippingMethod();
        $billingAddress = $order->getBillingAddress() ?? $order->getShippingAddress();
        
        $shippingMethod = $order->getShippingMethod(true);
        $carriers = ['bafreightstandard', 'bafreightexpress'];

        $shipping = [];

        $code = $shippingMethod->getData('carrier_code');
        
        if (in_array($code, $carriers)) {
            $source = $this->carrierSource->getCarriers();

            $shipping = [
                'Carrier_Service_ID' => $this->freightHelper->getCarrierId($code),
                'Carrier_Service_Text' => $source[$this->freightHelper->getCarrierId($code)]
            ];
        }

        return [
            'Order' => [
                'OrderHeader' => [
                    'ShipTo' => array_merge(
                        $shipping,
                        $this->getAddress($order->getShippingAddress())
                    ),
                    'BillTo' => $this->getAddress($billingAddress),
                ]
            ]
        ];
    }

    /**
     * Format address
     *
     * @param \Magento\Payment\Gateway\Data\Order\AddressAdapter $address
     * @return array
     */
    private function getAddress($addressAdapter)
    {
        //todo: Add address line 3
        $x = [
            'ContactName' => $addressAdapter->getFirstName() . ' ' . $addressAdapter->getLastName(),
            'Address1' => $addressAdapter->getStreetLine1(),
            'Address2' => $addressAdapter->getStreetLine2(),
            'Address3' => '',
            'City' => $addressAdapter->getCity(),
            'PostCode' => $addressAdapter->getPostCode(),
            'County' => $addressAdapter->getRegionCode() ?? 'N/A',
            'Country' => $addressAdapter->getCountryId()
        ];

        return $x;
    }
}
