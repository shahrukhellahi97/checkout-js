<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\AttributeCollectionInterface;
use BA\Punchout\Api\Data\DTOs\Types\AttributeInterface;

class AttributeCollection extends AbstractType implements AttributeCollectionInterface
{
    /**
     * @var \BA\Punchout\Model\DTOs\Types\AttributeFactory
     */
    protected $attributeFactory;

    public function __construct(
        \BA\Punchout\Model\DTOs\Types\AttributeFactory $attributeFactory,
        array $data = []
    ) {
        $this->attributeFactory = $attributeFactory;
        parent::__construct($data);
    }

    public function getAttributes()
    {
        if (!$this->hasData(AttributeCollectionInterface::ATTRIBUTES)) {
            $this->setAttributes([]);
        }

        return $this->getData(AttributeCollectionInterface::ATTRIBUTES);
    }

    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $attr) {
            if (is_array($attr)) {
                if (array_key_exists('key', $attr) && array_key_exists('value', $attr)) {
                    $attributes[$key] = $this->attributeFactory->create()
                        ->setKey($attr['key'])
                        ->setValue($attr['value']);
                }
            }
        }

        return $this->setData(AttributeCollectionInterface::ATTRIBUTES, $attributes);
    }

    public function toArray(array $keys = [])
    {
        $result = parent::toArray();
        
        if (isset($result[AttributeCollectionInterface::ATTRIBUTES])) {
            $map = [];
            // Map [key=a, value=c] to a=>c
            foreach ($result[AttributeCollectionInterface::ATTRIBUTES] as $key => $value) {
                $map[$value['key']] = $value['value'];
            }

            $result[AttributeCollectionInterface::ATTRIBUTES] = $map;
        }

        return $result;
    }

    public function getAttributeByKey(string $keyName)
    {
        return $this->getData($keyName);
    }
}