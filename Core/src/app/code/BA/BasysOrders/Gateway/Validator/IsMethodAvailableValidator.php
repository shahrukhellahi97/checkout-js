<?php
namespace BA\BasysOrders\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ValidatorInterface;

class IsMethodAvailableValidator extends AbstractValidator
{
    public function validate(array $validationSubject)
    {
        return $this->createResult(true);
    }
}