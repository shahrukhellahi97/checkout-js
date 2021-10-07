<?php
namespace BA\Newsletter\Api;

interface PostEmailManagementInterface
{

    /**
     * POST for postEmail api
     * @param string $param
     * @return string
     */
    public function postPostEmail($param);
}
