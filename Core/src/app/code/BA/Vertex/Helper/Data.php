<?php
namespace BA\Vertex\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const XML_PATH_VERTEX_EXPIRATION = 'basys/vertex/expiration';

    const XML_PATH_VERTEX_DEFAULT = 'basys/vertex/default';

    /**
     * Get expiray date in seconds
     * 
     * @return int
     */
    public function getRateExpiryDays()
    {
        return 60 * 60 * $this->scopeConfig->getValue(
            self::XML_PATH_VERTEX_EXPIRATION
        );
    }

    public function getDefaultRate()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_VERTEX_DEFAULT
        );
    }
}