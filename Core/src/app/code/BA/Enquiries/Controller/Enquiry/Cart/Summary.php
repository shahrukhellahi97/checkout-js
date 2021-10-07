<?php
namespace BA\Enquiries\Controller\Enquiry\Cart;

use BA\Enquiries\Api\EnquiryManagementInterface;
use BA\Enquiries\Model\EnquiryFieldFactory;
use BA\Enquiries\Model\Request\RequestParserInterface;
use BA\Enquiries\Model\Submit\CartSummary;
use BA\Enquiries\Model\ValidationInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Summary implements HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var \BA\Enquiries\Api\EnquiryManagementInterface
     */
    protected $enquiryManagement;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var \BA\Enquiries\Model\Submit\CartSummary
     */
    protected $cartSummary;

    /**
     * @var \BA\Enquiries\Model\EnquiryFieldFactory
     */
    protected $enquiryFieldFactory;

    /**
     * @var \BA\Enquiries\Model\Request\RequestParserInterface
     */
    protected $requestParser;

    /**
     * @var \BA\Enquiries\Model\ValidationInterface
     */
    protected $validator;

    public function __construct(
        RequestInterface $request,
        JsonFactory $jsonFactory,
        EnquiryManagementInterface $enquiryManagement,
        EnquiryFieldFactory $enquiryFieldFactory,
        RequestParserInterface $requestParser,
        ValidationInterface $validator,
        \Magento\Checkout\Model\Cart $cart,
        CartSummary $cartSummary
    ) {
        $this->request = $request;
        $this->jsonFactory = $jsonFactory;
        $this->enquiryManagement = $enquiryManagement;
        $this->enquiryFieldFactory = $enquiryFieldFactory;
        $this->requestParser = $requestParser;
        $this->validator = $validator;
        // cart is deprecated, but cart service contract does not work on guest checkouts??
        $this->cart = $cart;
        $this->cartSummary = $cartSummary;
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();

        if ($quote = $this->cart->getQuote()) {
            $base = $this->enquiryManagement->create($quote);
            $enquiry = $this->requestParser->parse($this->request, $base);

            if ($this->validator->isValid($enquiry)) {
                if ($this->cartSummary->submit($enquiry)) {
                    return $result->setData([
                        'success' => true,
                    ]);
                }
            } else {
                return $result->setData([
                    'errors' => $this->validator->getMessages()
                ]);
            }
        }

        return $result->setData(['error' => __("An unexpected error occured, please try again later")]);
    }
}
