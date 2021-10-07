<?php
namespace BA\Enquiries\Model;

use BA\Enquiries\Api\Data\EnquiryInterface;
use Magento\Framework\DataObject;

class Validator extends AbstractValidator
{
    /**
     * @var \\BA\Enquiries\Model\Validator\DataObjectFactory
     */
    protected $fieldValidatorFactory;

    /**
     * @var array
     */
    protected $fieldErrors = [];

    public function __construct(
        \BA\Enquiries\Model\Validator\DataObjectFactory $validatorFactory,
        \BA\Enquiries\Model\Field\ValidatorFactory $fieldValidatorFactory
    ) {
        parent::__construct($validatorFactory);

        $this->fieldValidatorFactory = $fieldValidatorFactory;
    }
    protected function addRules(\BA\Enquiries\Model\Validator\DataObject $dataObject)
    {
        // Setup Simple Rules
        $emailValid = new \Magento\Framework\Validator\EmailAddress();
        $emailValid->setMessage(
            __('Valid email required'),
            \Zend_Validate_EmailAddress::INVALID,
        );

        $lengthValid = new \Magento\Framework\Validator\StringLength();
        $lengthValid->setMax(128);

        // Apply
        $dataObject->addRule($emailValid, EnquiryInterface::EMAIL);
        $dataObject->addRule($lengthValid, EnquiryInterface::EMAIL);
        $dataObject->addRule($lengthValid, EnquiryInterface::CONTACT_NAME);

        return $dataObject;
    }

    public function getMessages()
    {
        return array_merge(
            parent::getMessages(),
            $this->fieldErrors
        );
    }

    public function isValid(DataObject $object)
    {
        if ($object instanceof Enquiry) {
            $valid = $this->getValidator();

            $fieldErrors = [];

            /** @var \BA\Enquiries\Model\EnquiryField $field */
            foreach ($object->getAdditionalFields() as $field) {
                /** @var \BA\Enquiries\Model\Field\Validator $fieldValidator */
                $fieldValidator = $this->fieldValidatorFactory->create();

                if (!$fieldValidator->isValid($field)) {
                    $fieldErrors[$field->getName()] = array_values($fieldValidator->getMessages()['value']);

                    $this->fieldErrors = array_merge(
                        $this->fieldErrors,
                        $fieldErrors
                    );
                }
            }

            if (!empty($fieldErrors)) {
                return false;
            }

            return $valid->isValid($object);
        }

        return false;
    }
}
