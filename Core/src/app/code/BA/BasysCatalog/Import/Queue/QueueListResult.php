<?php
namespace BA\BasysCatalog\Import\Queue;

class QueueListResult implements QueueListResultInterface
{
    public function __construct(array $data = [])
    {
        $this->_data = $data;
    }

    public function getAll()
    {
        return $this->_data['products'];
    }

    public function getSize()
    {
        return count($this->getAll());
    }
}