<?php
namespace BA\BasysCustomer\Model\ResourceModel;

use BA\BasysCustomer\Model\CustomerContactFactory;
use BA\BasysCustomer\Api\Data\CustomerContactInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerContact extends AbstractDb
{
    public function _construct()
    {
        $this->_init(CustomerContactInterface::SCHEMA, CustomerContactInterface::CONTACT_ID);
        $this->_isPkAutoIncrement = false;
    }

    public function getCustomerContactForEmail(AbstractModel $model, $emailAddress, $divisionId, $currency)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(
                ['c' => $this->getMainTable()]
            )
            ->where(
                'c.email = ?',
                $emailAddress
            )
            ->where(
                'c.division_id = ?',
                $divisionId
            )
            ->where(
                'c.currency = ?',
                $currency
            );
        
        if ($data = $this->getConnection()->fetchRow($select)) {
            return $model->setData($data);
        }

        return null;
    }
}