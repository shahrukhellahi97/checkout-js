<?php
namespace BA\Enquiries\Helper;

use Magento\Framework\ObjectManagerInterface;

class FormFactory
{
    private const TYPES = [
        1 => \BA\Enquiries\Helper\Form\Special::class,
        2 => \BA\Enquiries\Helper\Form\Summary::class
    ];

    const TYPE_SPECIAL = 1;
    
    const TYPE_SUMMARY = 2;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @param int $type 
     * @return \BA\Enquiries\Helper\Form\AbstractEnquiryHelper 
     */
    public function create(int $type, $arguments = [])
    {
        if (isset(static::TYPES[$type])) {
            return $this->objectManager->create(
                self::TYPES[$type], $arguments
            );
        }
    }
}