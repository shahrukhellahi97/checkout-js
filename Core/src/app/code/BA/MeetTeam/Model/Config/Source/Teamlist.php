<?php
namespace BA\MeetTeam\Model\Config\Source;

use BA\MeetTeam\Model\ResourceModel\Request\CollectionFactory as RequestFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Teamlist implements OptionSourceInterface
{

    protected $requestModel;

    /**
     * MeetTeam constructor.
     *
     */
    public function __construct(RequestFactory $requestModel)
    {
        $this->requestModel = $requestModel;
    }

    /**
     * @return array
     */
    public function getTeam()
    {
        $meetTeamDatas =  $this->requestModel->create();
        return $meetTeamDatas;
    }

    /*
     * Option getter
     * @return array
     */
    public function toOptionArray()
    {
        $meetTeamDatas = $this->getTeam();
        $teamArray = [];

        foreach ($meetTeamDatas as $meetTeamData) {

            $teamArray[]= [
                'value' => $meetTeamData->getId(),
                'label' => $meetTeamData->getName()
            ];
        }

        return $teamArray;
    }
}
