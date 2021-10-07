<?php
namespace BA\Punchout\Model;

use BA\Punchout\Api\Data\RequestInterface;
use Magento\Framework\Validator\DataObject;
use Magento\Framework\Validator\NotEmpty;
use Zend\Validator\InArray;
use Magento\Framework\Validator\EmailAddress;

class RequestValidationRules
{
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currencyModel;

    public function __construct(
        \Magento\Directory\Model\Currency $currencyModel
    ) {
        $this->currencyModel = $currencyModel;
    }

    public function addCurrencyValidation(DataObject $validator)
    {
        $currencies = $this->currencyModel->getConfigAllowCurrencies();
    }

    /**
     * Add simple validation rules, ex: required fields have values, and are valid
     * 
     * @param \Magento\Framework\Validator\DataObject $validator 
     * @return \Magento\Framework\Validator\DataObject 
     * @throws \Zend_Validate_Exception 
     */
    public function addSimpleRules(DataObject $validator)
    {
        // 
        // Email Validation
        //-------------------------------
        $emailValid = new EmailAddress();
        $emailValid->setMessage(
            __('Valid email required'),
            \Zend_Validate_EmailAddress::INVALID,
        );

        $validator->addRule($emailValid, RequestInterface::EMAIL);

        // 
        // Not Empty Validations
        //-------------------------------
        $validateNotEmptyFields = [
            'Browser From Post ' => RequestInterface::BROWSER_FROM_POST,
            'Return URL ' => RequestInterface::RETURN_URL,
            'Email Address' => RequestInterface::EMAIL,
            // 'Contact Name'  => RequestInterface::NAME,
            'Currency'      => RequestInterface::CURRENCY,
            'Buyer Cookie'  => RequestInterface::BUYER_COOKIE,
        ];

        foreach ($validateNotEmptyFields as $label => $field) {
            $rule = new NotEmpty();
            $rule->setMessage(
                __(sprintf("'%s' is a required field", $label)), 
                \Zend_Validate_NotEmpty::IS_EMPTY
            );

            $validator->addRule($rule, $field);
        }

        return $validator;
    }
}