<?php
namespace BA\Vertex\Model\Request\Builder;

use Magento\Quote\Model\Quote;

class CalculateTaxRequest implements CalculateTaxRequestInterface
{
    /**
     * @var \BA\Vertex\Model\Request\Builder\CalculateTaxRequestInterface[]|array
     */
    protected $builders;

    public function __construct(
        array $builders = []
    ) {
        $this->builders = $builders;
    }

    public function build(Quote $quote)
    {
        $result = [];

        /** @var \BA\Vertex\Model\Request\Builder\CalculateTaxRequestInterface $builder */
        foreach ($this->builders as $builder) {
            $result = array_replace_recursive($result, $builder->build($quote));
        }

        $xml = new \SimpleXMLElement('<Tax />');
        $this->processToXml($xml, $result);
        
        $xmlString = explode("\n", $xml->asXML(), 2)[1];

        return [$xmlString];
    }

    private function processToXml(\SimpleXMLElement &$xml, array $result)
    {
        foreach ($result as $key => $data) {
            if (preg_match('/Country$/i', $key)) {
                $child = $xml->addChild($key, $data[1]);
                $child->addAttribute('countryCode', $data[0]);
            } else if (is_array($data)) {
                $child = $xml->addChild($key);
                $this->processToXml($child, $data);
            } else {
                $xml->addChild($key, $data);
            }
        }
    }
}
