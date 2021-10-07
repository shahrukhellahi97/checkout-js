<?php
namespace BA\Enquiries\ViewModel;

use BA\Enquiries\Api\Data\EnquiryFieldTypeInterface;
use BA\Enquiries\Model\Submit\CartSummary;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class EmailViewModel implements ArgumentInterface
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \BA\Enquiries\Api\Data\EnquiryInterface
     */
    protected $enquiry;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return \BA\Enquiries\Api\Data\EnquiryInterface
     */
    public function getEnquiry()
    {
        if (!$this->enquiry) {
            $this->enquiry = $this->dataPersistor->get(CartSummary::PERSISTENCE_KEY);
        }

        return $this->enquiry;
    }

    public function getDetails()
    {
        $result = [
            'Customer' => [
                'Contact Name' => $this->getEnquiry()->getContactName(),
                'Email Address' => $this->getEnquiry()->getEmail(),
            ],
        ];

        $additional = $this->getAdditional();

        if (count($additional) >= 1) {
            $result['Request Details'] = $additional;
        }

        return $result;
    }

    public function getAdditional()
    {
        $result = [];

        foreach ($this->getEnquiry()->getAdditionalFields() as $field) {
            $value = trim($field->getValue());

            if ($field->getType() == EnquiryFieldTypeInterface::TYPE_TEXT) {
                $value = nl2br($value);
            }

            if (!empty($value)) {
                $result[$field->getLabel()] = $value;
            }
        }

        return $result;
    }

    /**
     * @return \BA\Enquiries\Api\Data\EnquiryItemInterface[]
     */
    public function getEnquiryItems()
    {
        return $this->getEnquiry()->getItems();
    }

    public function format($value)
    {
        return $this->priceCurrency->format($value);
    }

    public function getTotalQuantity()
    {
        $sum = 0;

        foreach ($this->getEnquiryItems() as $item) {
            $sum += $item->getQuantity();
        }

        return $sum;
    }

    public function getTotal()
    {
        $sum = 0;

        foreach ($this->getEnquiryItems() as $item) {
            $sum += $item->getTotal();
        }

        return $sum;
    }
}
