<?php
namespace BA\Enquiries\Model;

abstract class AbstractValidator implements ValidationInterface
{
    /**
     * @var \BA\Enquiries\Model\Validator\DataObjectFactory
     */
    protected $validatorFactory;

    /**
     * @var \Magento\Framework\Validator\DataObject
     */
    protected $validator;

    public function __construct(
        \BA\Enquiries\Model\Validator\DataObjectFactory $validatorFactory
    ) {
        $this->validatorFactory = $validatorFactory;
    }

    public function getMessages()
    {
        return $this->getValidator()->getMessages();
    }

    /**
     * @return \BA\Enquiries\Model\Validator\DataObject
     */
    protected function getValidator()
    {
        if (!$this->validator) {
            $validitor = $this->validatorFactory->create();

            $this->validator = $this->addRules($validitor);
        }

        return $this->validator;
    }

    /**
     * @param \BA\Enquiries\Model\Validator\DataObject $dataObject 
     * @return \BA\Enquiries\Model\Validator\DataObject
     */
    protected function addRules(\BA\Enquiries\Model\Validator\DataObject $dataObject)
    {
        return $dataObject;
    }
}