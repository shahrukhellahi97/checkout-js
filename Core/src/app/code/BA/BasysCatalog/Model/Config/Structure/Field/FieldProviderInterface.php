<?php
namespace BA\BasysCatalog\Model\Config\Structure\Field;


interface FieldProviderInterface
{
    /**
     * @param mixed $catalog
     * @return array
     */
    public function process($catalog);
}
