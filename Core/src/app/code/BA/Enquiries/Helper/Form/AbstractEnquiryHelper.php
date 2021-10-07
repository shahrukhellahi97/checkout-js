<?php
namespace BA\Enquiries\Helper\Form;

use BA\Enquiries\Model\EnquiryFieldFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

abstract class AbstractEnquiryHelper extends AbstractHelper
{
    const XML_PATH_TEMPLATE = 'basys_enquiries/summary/template';
    
    const XML_PATH_TEMPLATE_ADMIN = 'basys_enquiries/summary/template_admin';

    const XML_PATH_FIELDS = 'basys_enquiries/summary/fields';

    const DEFAULT_TEMPLATE = 'enquiries_summary_template';

    const DEFAULT_TEMPLATE_ADMIN = 'enquiries_summary_template_admin';

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

    public function getTemplate()
    {
        $template = $this->scopeConfig->getValue(
            static::XML_PATH_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );

        return $template ?? static::DEFAULT_TEMPLATE;
    }

    public function getAdminTemplate()
    {
        $template = $this->scopeConfig->getValue(
            static::XML_PATH_TEMPLATE_ADMIN,
            ScopeInterface::SCOPE_STORE
        );

        return $template ?? static::DEFAULT_TEMPLATE_ADMIN;
    }

    /**
     * @return \BA\Enquiries\Model\EnquiryField[]|array
     */
    public function getFields()
    {
        return $this->mapRangeToObject(
            $this->scopeConfig->getValue(
                static::XML_PATH_FIELDS,
                ScopeInterface::SCOPE_STORE
            )
        );
    }

    private function mapRangeToObject($input)
    {
        $fields = [];

        $input = is_array($input) ? $input : json_decode($input, true);

        foreach ($input as $values) {
            $fields[] = $this->enquiryFieldFactory->create([
                'data' => $values
            ]);
        }

        return $fields;
    }
}