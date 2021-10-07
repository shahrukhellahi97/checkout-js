<?php
namespace BA\Enquiries\Api\Data;

interface EnquiryItemInterface
{
    const SKU = 'sku';
    const NAME = 'name';
    const QUANTITY = 'quantity';
    const LINE_ITEM_COST = 'line_item_cost';
    const TOTAL = 'total';
    const IMAGE = 'image';

    public function getSku();

    public function getName();

    public function getQuantity();

    public function getLineItemCost();

    public function getTotal();

    public function getImage();
}