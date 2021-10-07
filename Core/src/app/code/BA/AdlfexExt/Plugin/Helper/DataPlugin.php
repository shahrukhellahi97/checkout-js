<?php
namespace BA\AdflexExt\Plugin\Helper;

class DataPlugin
{
    public function aroundGetAsPence($subject, $grandTotal, callable $proceed)
    {
        return round($grandTotal * 100);
    }
}