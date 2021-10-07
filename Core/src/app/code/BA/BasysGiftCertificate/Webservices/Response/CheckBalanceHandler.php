<?php
namespace BA\BasysGiftCertificate\Webservices\Response;

use BA\Basys\Webservices\Response\HandlerInterface;

class CheckBalanceHandler implements HandlerInterface
{
    /**
     * @var \Magento\Framework\Xml\Parser
     */
    protected $parser;

    public function __construct(\Magento\Framework\Xml\Parser $parser)
    {
        $this->parser = $parser;
    }

    public function handle($response, array $additional = [])
    {
        $remainingBalance = $response['CheckBalanceResult'];
        $remainingBalance = str_replace("utf-16", "utf-8", $remainingBalance);
        $x = $this->parser->loadXML($remainingBalance)->xmlToArray();
        return $x['GiftCertificateCheckResult']['_value'];
    }
}
