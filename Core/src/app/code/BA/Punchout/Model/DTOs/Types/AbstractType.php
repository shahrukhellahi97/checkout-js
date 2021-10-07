<?php
namespace BA\Punchout\Model\DTOs\Types;

use Magento\Framework\DataObject;

abstract class AbstractType extends DataObject 
{ 
    public function getGetters()
    {
        $dataObjectKeys   = get_class_methods(\Magento\Framework\DataObject::class);
        $dataObjectKeys[] = 'getGetters';
        $getters = [];

        foreach (get_class_methods($this) as $method) {
            if (!in_array($method, $dataObjectKeys)) {
                if (preg_match('/^get(\w+)/', $method, $match)) {
                    $getters[] = $match[1];
                }
            }
        }

        return $getters;
    }

    public function toArray(array $keys = [])
    {
        if (empty($keys)) {
            return $this->toArrayRecursive($this->_data);
        }

        $result = [];
        foreach ($keys as $key) {
            if (isset($this->_data[$key])) {
                $result[$key] = $this->toArrayRecursive($this->_data[$key]);
            } else {
                $result[$key] = null;
            }
        }
        return $result;
    }
    
    public function toArrayRecursive($data)
    {
        $result = [];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if ($value instanceof DataObject) {
                    $result[$key] = $value->toArray();
                } else if (is_array($value)) {
                    $result[$key] = $this->toArrayRecursive($value);
                } else {
                    $result[$key] = $value;
                }
            }
        } else if ($data instanceof DataObject) {
            foreach ($data->getData() as $key => $value) {
                if ($value instanceof DataObject || is_array($value)) {
                    $result[$key] = $value->toArray();
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    public function toJson(array $keys = [])
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function toJsonRecursive($object)
    {
        $dataArray = [];

        if ($object instanceof DataObject) {
            foreach ($object->getData() as $key => $value) {
                if ($value instanceof DataObject || is_array($value)) {
                    $dataArray[$key] = $this->toJsonRecursive($value);
                } else {
                    $dataArray[$key] = $value;
                }
            }
        } else if (is_array($object)) {
            foreach ($object as $key => $value) {
                $dataArray[$key] = $this->toJsonRecursive($value);
            }
        }

        return $dataArray;
    }
}