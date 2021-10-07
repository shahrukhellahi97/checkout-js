<?php
namespace BA\Newsletter\Model;

class PostEmailManagement implements \BA\Newsletter\Api\PostEmailManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function postPostEmail($param)
    {
        return 'hello api POST return the $param ' . $param;
    }
}

