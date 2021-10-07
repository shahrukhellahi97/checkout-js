<?php
namespace BA\Basys\Framework\Amqp;

class Content extends \PhpAmqpLib\Wire\GenericContent
{
    public function __construct($body = '', $properties = array())
    {
        parent::__construct($body, $properties);
    }

    // public function get_properties()
    // {
    //     $properties = $this->get_properties();

    //     if (!isset($properties['topic_name'])) {
    //         if (preg_match('/^([0-9]\.)+(.*?)$/', $properties['routing_key'], $matches)) {
    //             $properties['topic_name'] = $matches[2];
    //         }
    //     }

    //     return $properties;
    // }   
}