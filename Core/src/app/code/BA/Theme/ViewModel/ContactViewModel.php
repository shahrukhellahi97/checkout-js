<?php
namespace BA\Theme\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class ContactViewModel implements ArgumentInterface
{
    private $address = "Broadway House,<br>Trafford Wharf Road,<br>Trafford Park,<br>Stretford,<br>Manchester,<br>M171DD.";
    private $times = " 8:00am - 6:00pm";
    private $email = "company@brandaddition.com";

    public function getAddress()
    {
        return $this->address;
    }

    public function getTimes()
    {
        return $this->times;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
