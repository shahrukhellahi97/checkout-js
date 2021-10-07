<?php
namespace BA\Enquiries\Helper;

use BA\Enquiries\Model\EnquiryFieldFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    // Defaults
    const DEFAULT_XML_PATH_FROM_EMAIL = 'trans_email/ident_general/email';

    const DEFAULT_XML_PATH_FROM_NAME = 'trans_email/ident_general/name';

    //
    const XML_PATH_OVERRIDE = 'basys_enquiries/general/override';
    
    const XML_PATH_FROM_EMAIL = 'basys_enquiries/general/from';

    const XML_PATH_FROM_NAME = 'basys_enquiries/general/from_name';

    const XML_PATH_ADMIN_EMAIL = 'basys_enquiries/general/admin';

    const XML_PATH_ADMIN_EMAIL_CC = 'basys_enquiries/general/admin_cc';

    const XML_PATH_ENQUIRIES_ONLY = 'basys_enquiries/general/enquiries_only';

    const XML_PATH_SUMMARY_ENABLED = 'basys_enquiries/summary/enabled';

    const XML_PATH_CUSTOM_FIELDS = 'basys_enquiries/custom/fields';

    const XML_PATH_SUMMARY_FIELDS = 'basys_enquiries/summary/fields';

    /**
     * @var \BA\Enquiries\Model\EnquiryFieldFactory
     */
    protected $enquiryFieldFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        EnquiryFieldFactory $enquiryFieldFactory
    ) {
        parent::__construct($context);

        $this->enquiryFieldFactory = $enquiryFieldFactory;
    }

    public function getUseCustomEmails()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_OVERRIDE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getIsSummaryEnabled()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_SUMMARY_ENABLED,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function getIsEnquiryOnly()
    {
        if ($this->getIsSummaryEnabled()) {
            return (bool) $this->scopeConfig->getValue(
                self::XML_PATH_ENQUIRIES_ONLY,
                ScopeInterface::SCOPE_WEBSITE
            );
        }

        return false;
    }

    public function getAdminEmail()
    {
        if ($this->getUseCustomEmails()) {
            return $this->scopeConfig->getValue(
                self::XML_PATH_ADMIN_EMAIL,
                ScopeInterface::SCOPE_STORE
            );
        } else {
            return $this->scopeConfig->getValue(
                self::DEFAULT_XML_PATH_FROM_EMAIL,
                ScopeInterface::SCOPE_STORE
            );
        }
    }

    public function getFromEmail()
    {
        if ($this->getUseCustomEmails()) {
            return $this->scopeConfig->getValue(
                self::XML_PATH_FROM_EMAIL,
                ScopeInterface::SCOPE_STORE
            );
        } else {
            return $this->scopeConfig->getValue(
                self::DEFAULT_XML_PATH_FROM_EMAIL,
                ScopeInterface::SCOPE_STORE
            );
        }
    }

    public function getFromName()
    {
        if ($this->getUseCustomEmails()) {
            return $this->scopeConfig->getValue(
                self::XML_PATH_FROM_NAME,
                ScopeInterface::SCOPE_STORE
            );
        } else {
            return $this->scopeConfig->getValue(
                self::DEFAULT_XML_PATH_FROM_NAME,
                ScopeInterface::SCOPE_STORE
            );
        }
    }

    public function getAdminCCs()
    {
        $result = [];
        
        if ($admins = $this->getAdminCCsAsArray()) {
            foreach ($admins as $admin) {
                $result[] = $admin['email'];
            }
        }

        return $result;
    }

    private function getAdminCCsAsArray()
    {
        $admins = $this->scopeConfig->getValue(
            self::XML_PATH_ADMIN_EMAIL_CC,
            ScopeInterface::SCOPE_STORE
        );

        $admins = is_array($admins) ? $admins : json_decode($admins, true);

        return $admins;
    }
}