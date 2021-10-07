<?php
namespace BA\Communigator\Plugin\Newsletter;

use BA\Communigator\Api\CommunigatorManagementInterface;
use Magento\Newsletter\Model\Subscriber;

/*

Client ID: BrandAddition
Client Secret: Iy57wSi^$Q&^OMHS

*/
class SubscriberPlugin
{
    protected $communigatorManagement;

    public function __construct(CommunigatorManagementInterface $communigatorManagement)
    {
        $this->communigatorManagement = $communigatorManagement;
    }

    public function afterConfirm(
        Subscriber $subject,
        $result
    ) {
        if ($subject->getStatus() == Subscriber::STATUS_SUBSCRIBED) {
            $this->communigatorManagement->subscribe(
                $subject->getEmail()
            );
        }

        return $result;
    }
}