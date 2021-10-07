<?php
namespace BA\BasysOrders\Model\ResourceModel;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldOptionInterface;
use BA\BasysOrders\Model\PaymentType as PaymentTypeModel;
use BA\BasysOrders\Model\PaymentTypeFactory;
use BA\BasysOrders\Model\UserDefinedFieldFactory;
use BA\BasysOrders\Model\UserDefinedFieldOptionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PaymentType extends AbstractDb
{
    /**
     * @var \BA\BasysOrders\Model\PaymentTypeFactory
     */
    protected $paymentTypeFactory;

    /**
     * @var \BA\BasysOrders\Model\UserDefinedFieldFactory
     */
    protected $userDefinedFieldFactory;

    /**
     * @var \BA\BasysOrders\Model\UserDefinedFieldOptionFactory
     */
    protected $userDefinedFieldOptionFactory;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        PaymentTypeFactory $paymentTypeFactory,
        UserDefinedFieldFactory $userDefinedFieldFactory,
        UserDefinedFieldOptionFactory $userDefinedFieldOptionFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->paymentTypeFactory = $paymentTypeFactory;
        $this->userDefinedFieldFactory = $userDefinedFieldFactory;
        $this->userDefinedFieldOptionFactory = $userDefinedFieldOptionFactory;
    }

    protected function _construct()
    {
        $this->_init(PaymentTypeInterface::SCHEMA, PaymentTypeInterface::DIVISION_ID);
        $this->_isPkAutoIncrement = false;
    }

    public function getPaymentType($divisionId, $paymentTypeId, $includeUdfs = false, $paymentMethod = null)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable())
            ->where(
                'division_id = ?',
                $divisionId
            )
            ->where(
                'payment_type_id = ?',
                $paymentTypeId
            );

        if ($paymentMethod != null) {
            if (is_array($paymentMethod)) {
                $select = $select->where(
                    'method IN (?)',
                    $paymentMethod
                );
            } else {
                $select = $select->where('method = ?', $paymentMethod);
            }
        }

        $a = $select->__toString();

        if ($data = $connection->fetchRow($select)) {
            /** @var \BA\BasysOrders\Model\PaymentType $model */
            $model = $this->paymentTypeFactory->create();
            $model->setData($data);

            if ($includeUdfs) {
                $this->getUserDefinedFields($model);
            }

            return $model;
        }

        return null;
    }

    private function getUserDefinedFields(PaymentTypeModel $model)
    {
        // THIS IS NOT FINAL, THIS IS LAZY CODING.
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(
                ['type' => $this->getMainTable()],
            )
            ->joinInner(
                ['map' => $this->getTable('ba_basys_orders_payment_type_udf')],
                'map.payment_type_id = type.payment_type_id AND map.division_id = type.division_id',
            )
            ->joinInner(
                ['udf' => $this->getTable(UserDefinedFieldInterface::SCHEMA)],
                'udf.sequence_id = map.sequence_id AND udf.division_id = map.division_id'
            )
            ->where(
                'type.division_id = ?',
                $model->getDivisionId(),
            )
            ->where(
                'type.payment_type_id = ?',
                $model->getPaymentTypeId(),
            );

        $x = $select->__toString();
        $udfs = [];

        foreach ($connection->fetchAll($select) as $row) {
            /** @var \BA\BasysOrders\Model\UserDefinedField $udf */
            $udf = $this->userDefinedFieldFactory->create();
            $udf->setData($row);

            $udfs[] = $udf;
        }

        // Get options, this needs some re-looking at in the future. this is lazy.
        $select = $connection->select()
            ->from($this->getTable(UserDefinedFieldOptionInterface::SCHEMA))
            ->where(
                'division_id = ?',
                $model->getDivisionId()
            )
            ->where(
                'sequence_id IN (?)',
                array_map(function ($a) {
                    /** @var \BA\BasysOrders\Model\UserDefinedField $a */
                    return $a->getSequenceNo();
                }, $udfs)
            );

        $options = [];

        foreach ($connection->fetchAll($select) as $row) {
            /** @var \BA\BasysOrders\Model\UserDefinedFieldOption $option */
            $option = $this->userDefinedFieldOptionFactory->create();
            $option->setData($row);

            $options[$option->getSequenceNo()][] = $option;
        }

        /** @var \BA\BasysOrders\Model\UserDefinedField $udf */
        foreach ($udfs as $udf) {
            if (isset($options[$udf->getSequenceNo()])) {
                $udf->setOptions($options[$udf->getSequenceNo()]);
            }
        }

        $model->setUserDefinedFields($udfs);
    }

    public function save(AbstractModel $object)
    {
        /** @var \BA\BasysOrders\Model\PaymentType $object */
        $transaction = $this->getConnection()->beginTransaction();

        $transaction->insertOnDuplicate(
            $this->getMainTable(),
            $this->flatten(PaymentTypeInterface::KEYS, $object)
        );

        if (is_array($object->getUserDefinedFields())) {
            /** @var \BA\BasysOrders\Model\UserDefinedField $udf */
            foreach ($object->getUserDefinedFields() as $udf) {
                $transaction->insertOnDuplicate(
                    $this->getTable('ba_basys_orders_payment_type_udf'),
                    [
                        'division_id' => $object->getDivisionId(),
                        'payment_type_id' => $object->getPaymentTypeId(),
                        'sequence_id' => $udf->getSequenceNo(),
                    ]
                );

                $transaction->insertOnDuplicate(
                    $this->getTable(UserDefinedFieldInterface::SCHEMA),
                    $this->flatten(UserDefinedFieldInterface::KEYS, $udf)
                );

                /** @var \BA\BasysOrders\Model\UserDefinedFieldOption $option */
                foreach ($udf->getOptions() as $option) {
                    $transaction->insertOnDuplicate(
                        $this->getTable(UserDefinedFieldOptionInterface::SCHEMA),
                        $this->flatten(UserDefinedFieldOptionInterface::KEYS, $option)
                    );
                }
            }
        }

        $transaction->commit();
    }

    private function flatten(array $keys, AbstractModel $object)
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $object->getData($key);
        }

        return $result;
    }
}
