<?php
namespace BA\Punchout\Model;

use BA\Punchout\Api\Data\CredentialInterface;
use BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface;
use BA\Punchout\Api\Data\RequestInterface;
use BA\Punchout\Model\DTOs\Types\AbstractType;
use BA\Punchout\Model\ResourceModel\Request as ResourceModelRequest;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class Request extends AbstractModel implements RequestInterface
{
    /**
     * @var \Magento\Framework\Validator\DataObjectFactory
     */
    protected $validatorObjectFactory;

    /**
     * @var \BA\Punchout\Model\RequestValidationRules
     */
    protected $validationRules;

    /**
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $stringUtils;

    /**
     * @var \BA\Punchout\Model\CredentialFactory
     */
    protected $credentialFactory;

    /**
     * @var \BA\Punchout\Model\Credential[]
     */
    protected $credentials = [];

    /**
     * @var \BA\Punchout\Model\ResourceModel\Credential\CollectionFactory
     */
    protected $credentialCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Validator\DataObjectFactory $validatorObjectFactory,
        RequestValidationRules $validationRules,
        \Magento\Framework\Stdlib\StringUtils $stringUtils,
        \BA\Punchout\Model\CredentialFactory $credentialFactory,
        \BA\Punchout\Model\ResourceModel\Credential\CollectionFactory $credentialCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,        
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->credentialCollectionFactory = $credentialCollectionFactory;
        $this->stringUtils = $stringUtils;
        $this->validatorObjectFactory = $validatorObjectFactory;
        $this->validationRules = $validationRules;
        $this->credentialFactory = $credentialFactory;
    }

    protected function _construct()
    {
        $this->_init(ResourceModelRequest::class);
    }

    protected function _getValidationRulesBeforeSave()
    {
        /** @var \Magento\Framework\Validator\DataObject */
        $validator = $this->validatorObjectFactory->create();
        
        $this->validationRules->addSimpleRules($validator);
        $this->validationRules->addCurrencyValidation($validator);

        return $validator;
    }

    public function createWithSetupRequest(SetupRequestInterface $request)
    {
        // Handle Headers
        if ($request->getHeader()->getSender()->getIdentity() != null) { 
            $this->addCredential($request->getHeader()->getSender(), CredentialType::SENDER);
        }

        if ($request->getHeader()->getFrom()->getIdentity() != null) { 
            $this->addCredential($request->getHeader()->getFrom(), CredentialType::FROM);
        }

        if ($request->getHeader()->getTo()->getIdentity() != null) {
            $this->addCredential($request->getHeader()->getTo(), CredentialType::TO);
        }

        //
        // Handle mapping of payload to model
        //--------------------------------------
        $this->mapDTOToModel($this, $request);

        /** @var \BA\Punchout\Api\Data\DTOs\Request\Body\SetupRequestBodyInterface $body */
        $body = $request->getPayload();

        $this->mapDTOToModel($this, $body);

        // Reset the URLs
        $this->setBrowserFromPost($body->getBrowserFromPost()->getUrl());
        $this->setReturnUrl($body->getReturnUrl()->getUrl());

        // Add contact DTO
        $this->mapDTOToModel($this, $body->getContact());

        // Add shipping if exists.
        if ($body->getShipTo() !== null) {
            $this->mapDTOToModel($this, $body->getShipTo());
        }
    }

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface|BA\Punchout\Api\Data\CredentialInterface $credential 
     * @param int $credentialType 
     * @return $this 
     */
    public function addCredential($credential, int $credentialType)
    {
        if ($credential instanceof \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface) {
            /** @var \BA\Punchout\Model\Credential $model */
            $model = $this->credentialFactory->create();

            $model->setTypeId($credentialType);
            $model->setIdentity($credential->getIdentity());

            $interfaceMethods = get_class_methods(\BA\Punchout\Api\Data\CredentialInterface::class);

            /** @var \BA\Punchout\Api\Data\DTOs\Types\AttributeInterface $attribute */
            if ($credential->getAttributes() != null) {
                foreach ($credential->getAttributes() as $attribute) {
                    $method = $this->stringUtils->upperCaseWords($attribute->getKey(), '_', '');
                    $setMethod = sprintf('set%s', $method);

                    if (in_array($setMethod, $interfaceMethods)) {
                        $model->$setMethod($attribute->getValue());
                    }
                }
            }

            $credential = $model;
        }

        if ($credential instanceof \BA\Punchout\Api\Data\CredentialInterface) {
            $this->credentials[$credentialType] = $credential;
        }

        return $this;
    }

    /**
     * @return \BA\Punchout\Model\Credential[] 
     */
    public function getCredentials()
    {
        if ($this->credentials == null) {
            /** @var \BA\Punchout\Model\ResourceModel\Credential\Collection */
            $credentials = $this->credentialCollectionFactory->create();            
            $credentials->addFieldToFilter('request_id', $this->getRequestId())
                ->fetchItem();

            /** @var \BA\Punchout\Model\Credential $credential */
            foreach ($credentials as $credential) {
                $this->credentials[$credential->getTypeId()] = $credential;
            }
        }

        return $this->credentials;
    }

    /**
     * @param int $credentialType 
     * @return \BA\Punchout\Model\Credential|null 
     */
    public function getCredentialByType(int $credentialType)
    {
        $credentials = $this->getCredentials();

        if (isset($credentials[$credentialType])) {
            return $credentials[$credentialType];
        }

        return null;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $model 
     * @param \BA\Punchout\Model\DTOs\Types\AbstractType $dto 
     * @return void 
     * @throws \Magento\Framework\Exception\LocalizedException 
     */
    private function mapDTOToModel($model, $dto): void
    {
        if ($dto instanceof AbstractType) {
            foreach ($dto->getGetters() as $getter) {
                $setMethod = sprintf('set%s', $getter);
                $getMethod = sprintf('get%s', $getter);
                
                if (method_exists($model, $setMethod) && method_exists($model, $getMethod)) {
                    try {
                        $model->$setMethod($dto->$getMethod());
                    } catch (\Exception $e) {
                        throw new LocalizedException(__(sprintf('unable to map %s to model', $getter)));
                    }
                }
            }
        }
    }

    public function getStoreId()
    {
        return $this->getData(RequestInterface::STORE_ID);
    }

    public function setStoreId($id)
    {
        return $this->setData(RequestInterface::STORE_ID, $id);
    }

    public function getCustomerId()
    {
        return $this->getData(RequestInterface::CUSTOMER_ID);
    }

    public function setCustomerId($id)
    {
        return $this->setData(RequestInterface::CUSTOMER_ID, $id);
    }

    public function getToken()
    {
        return $this->getData(RequestInterface::TOKEN);
    }

    /**
     * Generate a random token
     * 
     * @return string 
     */
    public function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function setToken($token)
    {
        return $this->setData(RequestInterface::TOKEN, $token);
    }


    public function getRequestId()
    {
        return $this->getData(RequestInterface::REQUEST_ID);
    }

    public function setRequestId($id)
    {
        return $this->setData(RequestInterface::REQUEST_ID, $id);
    }

    public function getPayloadId()
    {
        return $this->getData(RequestInterface::PAYLOAD_ID);
    }

    public function setPayloadId($payloadId)
    {
        return $this->setData(RequestInterface::PAYLOAD_ID, $payloadId);
    }

    public function getTimestamp()
    {
        return $this->getData(RequestInterface::TIMESTAMP);
    }

    public function setTimestamp($timestamp)
    {
        return $this->setData(RequestInterface::TIMESTAMP, $timestamp);
    }

    public function getEmail()
    {
        return $this->getData(RequestInterface::EMAIL);
    }

    public function setEmail($emailAddress)
    {
        return $this->setData(RequestInterface::EMAIL, $emailAddress);
    }

    public function getName()
    {
        return $this->getData(RequestInterface::NAME);
    }

    public function setName($customerName)
    {
        return $this->setData(RequestInterface::NAME, $customerName);
    }

    public function getCurrency()
    {
        return $this->getData(RequestInterface::CURRENCY);
    }

    public function setCurrency($currency)
    {
        return $this->setData(RequestInterface::CURRENCY, $currency);
    }

    public function getProcurementApplication()
    {
        return $this->getData(RequestInterface::PROCUREMENT_APPLICATION);
    }

    public function setProcurementApplication($applicationName)
    {
        return $this->setData(RequestInterface::PROCUREMENT_APPLICATION, $applicationName);
    }

    public function getBuyerCookie()
    {
        return $this->getData(RequestInterface::BUYER_COOKIE);
    }

    public function setBuyerCookie($cookie)
    {
        return $this->setData(RequestInterface::BUYER_COOKIE, $cookie);
    }

    public function getBrowserFromPost()
    {
        return $this->getData(RequestInterface::BROWSER_FROM_POST);
    }

    public function setBrowserFromPost($url)
    {
        return $this->setData(RequestInterface::BROWSER_FROM_POST, $url);
    }

    public function getReturnUrl()
    {
        return $this->getData(RequestInterface::RETURN_URL);
    }

    public function setReturnUrl($url)
    {
        return $this->setData(RequestInterface::RETURN_URL, $url);
    }

    public function getDeliverTo()
    {
        return $this->getData(RequestInterface::DELIVER_TO);
    }

    public function setDeliverTo($deliverTo)
    {
        return $this->setData(RequestInterface::DELIVER_TO, $deliverTo);
    }

    public function getStreet()
    {
        return $this->getData(RequestInterface::STREET);
    }

    public function setStreet($street)
    {
        return $this->setData(RequestInterface::STREET, $street);
    }

    public function getCity()
    {
        return $this->getData(RequestInterface::CITY);
    }

    public function setCity($city)
    {
        return $this->setData(RequestInterface::CITY, $city);
    }

    public function getState()
    {
        return $this->getData(RequestInterface::STATE);
    }

    public function setState($state)
    {
        return $this->setData(RequestInterface::STATE, $state);
    }

    public function getPostalCode()
    {
        return $this->getData(RequestInterface::POSTCODE);
    }

    public function setPostalCode($postcode)
    {
        return $this->setData(RequestInterface::POSTCODE, $postcode);
    }

    public function getCountry()
    {
        return $this->getData(RequestInterface::COUNTRY);
    }

    public function setCountry($country)
    {
        return $this->setData(RequestInterface::COUNTRY, $country);
    }

}