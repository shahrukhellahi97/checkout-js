<?php
namespace BA\BasysCustomer\ViewModel;

use BA\BasysCustomer\Model\Field\TextFieldFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class AdditionalFields implements ArgumentInterface
{
    /**
     * @var \BA\BasysCustomer\Model\Field\TextFieldFactory
     */
    protected $textFieldFactory;

    public function __construct(TextFieldFactory $textFieldFactory)
    {
        $this->textFieldFactory = $textFieldFactory;
    }

    /**
     * Get list of additional fields
     *
     * @return BA\BasysCustomer\Model\Field\FieldInterface[]|array
     */
    public function getFields()
    {
        $result = [];

        foreach (['VAT Number', 'Dealer Number', 'Employee Number'] as $id => $label) {
            $result[] = $this->textFieldFactory->create([
                'data' => [
                    'label' => $label,
                    'code' => 'attr_' . $id
                ]
            ]);
        }

        return $result;
    }
}
