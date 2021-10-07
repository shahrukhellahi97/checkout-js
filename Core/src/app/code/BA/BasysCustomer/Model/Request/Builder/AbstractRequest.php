<?php
namespace BA\BasysCustomer\Model\Request\Builder;

abstract class AbstractRequest
{
    /**
     * @var array
     */
    protected $builders;

    public function __construct(array $builders = [])
    {
        $this->builders = $builders;
    }

    public function merge(array $result, array $builder)
    {
        return array_replace_recursive($result, $builder);
    }
}