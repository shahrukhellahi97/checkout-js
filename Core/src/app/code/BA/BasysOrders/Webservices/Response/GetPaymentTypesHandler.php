<?php
namespace BA\BasysOrders\Webservices\Response;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\BasysOrders\Model\PaymentType;
use BA\BasysOrders\Model\PaymentTypeFactory;
use BA\BasysOrders\Model\UserDefinedFieldFactory;

class GetPaymentTypesHandler implements HandlerInterface
{
    /**
     * @var \Magento\Framework\Xml\Parser
     */
    protected $parser;

    /**
     * @var \BA\BasysOrders\Model\PaymentTypeFactory
     */
    protected $paymentTypeFactory;

    /**
     * @var \BA\BasysOrders\Model\UserDefinedFieldFactory
     */
    protected $userDefinedFieldFactory;

    public function __construct(
        \Magento\Framework\Xml\Parser $parser,
        PaymentTypeFactory $paymentTypeFactory,
        UserDefinedFieldFactory $userDefinedFieldFactory
    ) {
        $this->parser = $parser;
        $this->paymentTypeFactory = $paymentTypeFactory;
        $this->userDefinedFieldFactory = $userDefinedFieldFactory;
    }

    public function handle($response, array $additional = [])
    {
        $xml = $response['DivisionPaymentTypesResult']['any'];
        $array = $this->parser->loadXML($xml)->xmlToArray();

        $types   = [];
        $objects = [];

        try {
            $objects = $array['PaymentTypes']['PaymentType'];
        } catch (\Exception $e) {
            return [];
        }

        $divisionId = $additional['division_id'] ?? null;

        foreach ($objects as $object) {
            $types[] = $this->createModel($object, $divisionId);
        }

        return $types;
    }

    /**
     * @param array $response
     * @param int|null $divisionId
     * @return \BA\BasysOrders\Model\PaymentType
     */
    private function createModel(array $response, $divisionId = null)
    {
        /** @var \BA\BasysOrders\Model\PaymentType $object */
        $object = $this->paymentTypeFactory->create();

        $object->setPaymentTypeId($response['PaymentTypeID'])
            ->setMethod($response['PaymentMethod'])
            ->setReference($response['PaymentTypeRef'])
            ->setDefault($response['IsDefault'] == 'Y' ? true : false);

        if ($divisionId != null) {
            $object->setDivisionId($divisionId);
        }

        if (is_array($response['UDFS'])) {
            $fields = [];

            // Webservices are dumb
            $keys = array_keys($response['UDFS']['UDF']);

            if (preg_match('/^[0-9]+$/i', $keys[0])) {
                foreach ($response['UDFS']['UDF'] as $node) {
                    $fields[] = $this->createUDFModel($object, $node);
                }
            } else {
                $fields[] = $this->createUDFModel($object, $response['UDFS']['UDF']);
            }

            $object->setUserDefinedFields($fields);
        }

        return $object;
    }

    /**
     * @param \BA\BasysOrders\Model\PaymentType $object
     * @param array $response
     * @return \BA\BasysOrders\Model\UserDefinedField
     */
    private function createUDFModel(PaymentType $object, array $response)
    {
        /** @var \BA\BasysOrders\Model\UserDefinedField $udf */
        $udf = $this->userDefinedFieldFactory->create();
        $caseRule = isset($response['CaseRule']) ? $response['CaseRule'] : 'N';

        $udf->setCaption($response['Caption'])
            ->setSequenceNo($response['SequenceNo'])
            ->setDivisionId($object->getDivisionId())
            ->setRule($response['RuleType'])
            ->setUppercase($caseRule == 'Y' ? true : false);

        if (is_array($response['PickListItems'])) {
            foreach ($response['PickListItems']['PickListItem'] as $option) {
                $udf->addOption(
                    $option['Value'],
                    $option['IsDefault'] == 'Y' ? true : false
                );
            }
        }

        return $udf;
    }
}
