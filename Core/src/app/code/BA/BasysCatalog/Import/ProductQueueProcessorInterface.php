<?php
namespace BA\BasysCatalog\Import;

interface ProductQueueProcessorInterface
{
    public function process(int $catalogId, array $products);
}