<?php
namespace BA\Basys\Webservices\Command;

class CommandArgument
{
    /**
     * @var mixed
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }   

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }
}