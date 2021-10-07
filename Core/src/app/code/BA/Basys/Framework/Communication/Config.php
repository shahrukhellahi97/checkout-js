<?php
namespace BA\Basys\Framework\Communication;

class Config extends \Magento\Framework\Communication\Config
{
    public function getTopic($topicName)
    {
        if (preg_match('/^([0-9]\.)+(.*?)$/', $topicName, $matches)) {
            $topicName = $matches[2];
        }

        return parent::getTopic($topicName);
    }
}