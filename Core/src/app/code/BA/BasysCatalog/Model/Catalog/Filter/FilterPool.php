<?php
namespace BA\BasysCatalog\Model\Catalog\Filter;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\Catalog\Filter\FilterInterface;
use Psr\Log\LoggerInterface;

class FilterPool implements FilterInterface
{
    /**
     * @var \BA\BasysCatalog\Model\Catalog\Filter\FilterInterface[]|array
     */
    protected $filters;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        array $filters = []
    ) {
        $this->logger = $logger;
        $this->filters = $filters;
    }

    public function test(CatalogInterface $catalog): bool
    {
        foreach ($this->filters as $name => $filter) {
            if ($filter instanceof FilterInterface) {
                if (!$filter->test($catalog)) {
                    return false;
                }
            }
        }

        return true;
    }
}
