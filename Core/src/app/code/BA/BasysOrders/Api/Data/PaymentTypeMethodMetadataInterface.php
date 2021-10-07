<?php
namespace BA\BasysOrders\Api\Data;

interface PaymentTypeMethodMetadataInterface
{
    const METHOD_CREDIT = 'C';
    const METHOD_INVOICE = 'I';
    const METHOD_CONSOLIDATED_INVOICE = 'G';
}