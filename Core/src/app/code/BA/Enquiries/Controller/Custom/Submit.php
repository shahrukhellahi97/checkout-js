<?php
namespace BA\Enquiries\Controller\Custom;

use BA\Enquiries\Helper\Data;
use BA\Enquiries\Helper\FormFactory;
use BA\Enquiries\Model\EnquiryFactory;
use BA\Enquiries\Model\Request\RequestParserInterface;
use BA\Enquiries\Model\Submit\SpecialRequest;
use BA\Enquiries\Model\ValidationInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\UrlInterface;

class Submit implements HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \BA\Enquiries\Model\Request\RequestParserInterface
     */
    protected $requestParser;

    /**
     * @var \BA\Enquiries\Model\ValidationInterface
     */
    protected $validator;

    /**
     * @var \BA\Enquiries\Model\EnquiryFactory
     */
    protected $enquiryFactory;

    /**
     * @var \BA\Enquiries\Helper\Data
     */
    protected $enquiriesHelper;

    /**
     * @var \BA\Enquiries\Model\Submit\SpecialRequest
     */
    protected $specialRequest;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @var \BA\Enquiries\Helper\FormFactory
     */
    protected $formFactory;

    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory,
        EnquiryFactory $enquiryFactory,
        Data $enquiriesHelper,
        RequestParserInterface $requestParser,
        SpecialRequest $specialRequest,
        FormFactory $formFactory,
        UrlInterface $url,
        ValidationInterface $validator
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->enquiryFactory = $enquiryFactory;
        $this->requestParser = $requestParser;
        $this->validator = $validator;
        $this->enquiriesHelper = $enquiriesHelper;
        $this->formFactory = $formFactory;
        $this->specialRequest = $specialRequest;
        $this->url = $url;
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $enquiry = $this->getEnquiryObject();

        if ($this->validator->isValid($enquiry)) {
            if ($this->specialRequest->submit($enquiry)) {
                return $result->setData([
                    'success' => $this->url->getUrl(
                        'enquiries/custom/success',
                        []
                    )
                ]);
            }
        } else {
            return $result->setData([
                'errors' => $this->validator->getMessages()
            ]);
        }

        return $result->setData(['error' => __("An unexpected error occured, please try again later")]);
    }

    /**
     * @return \BA\Enquiries\Model\Enquiry
     */
    private function getEnquiryObject()
    {
        $helper = $this->formFactory->create(
            \BA\Enquiries\Helper\FormFactory::TYPE_SPECIAL
        );

        $enquiry = $this->enquiryFactory->create();
        $enquiry->setAdditionalFields(
            $helper->getFields()
        );

        return $this->requestParser->parse($this->request, $enquiry);
    }
}
