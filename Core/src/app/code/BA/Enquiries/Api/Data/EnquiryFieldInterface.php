<?php
namespace BA\Enquiries\Api\Data;

interface EnquiryFieldInterface
{
    const NAME = 'name';
    const LABEL = 'label';
    const VALUE = 'value';
    const IS_REQUIRED = 'is_required';
    const TYPE = 'type';

    public function getName();

    public function setName($name);

    public function getLabel();

    public function setLabel($label);

    public function getValue();

    public function setValue($value);

    public function getIsRequired();

    public function setIsRequired($value);

    public function getType();

    public function setType($type);
}
