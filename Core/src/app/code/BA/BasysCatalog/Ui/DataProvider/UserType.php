<?php
namespace BA\BasysCatalog\Ui\DataProvider;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class UserType extends DataProvider
{
    public function getData()
    {
        return [
            '1' => [
                'catalog' => [
                    'name' => 'jimmy'
                ],
            ]
        ];
    }   
}