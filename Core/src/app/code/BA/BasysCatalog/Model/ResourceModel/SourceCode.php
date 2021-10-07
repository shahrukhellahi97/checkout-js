<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\BasysCatalog\Api\Data\SourceCodeInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SourceCode extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(SourceCodeInterface::SCHEMA_NAME, SourceCodeInterface::ENTITY_ID);
    }

    public function getSourceCodes()
    {
        
    }
}