<?php
namespace BA\Enquiries\Model;

use Magento\Framework\DataObject;

interface ValidationInterface
{
    /**
     * @param \Magento\Framework\DataObject $object 
     * @return bool 
     * @throws \Exception 
     */
    public function isValid(DataObject $object);

    /**
     * @return array 
     */
    public function getMessages();
}