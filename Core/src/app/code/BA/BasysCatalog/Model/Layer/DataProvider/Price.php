<?php
namespace BA\BasysCatalog\Model\Layer\DataProvider;

use BA\BasysCatalog\Model\ResourceModel\Layer\Filter\Price as PriceResource;
use Psr\Log\LoggerInterface;

class Price
{
    /**
     * @var array
     */
    protected $ranges;

    /**
     * @var int[]|array
     */
    protected $divisions;

    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Layer\Filter\Price
     */
    protected $basysPricesResource;

    public function __construct(
        LoggerInterface $logger,
        PriceResource $basysPricesResource
    ) {
        $this->logger = $logger;
        $this->basysPricesResource = $basysPricesResource;
    }

    /**
     * Calculating the intervals between the price from the higher price
     * @param array $maxPriceArr
     * @return int
     */
    public function getInterval($maxPriceArr)
    {
        $interval = pow(10, strlen(floor($maxPriceArr['price'])) - 1);

        if ($interval<10) {
            $interval = 10;
        }
        return $interval;
    }

    /**
     * Get the nearest multiple of 10 less than the min price in the list
     * @param array $minPriceArr
     * @param int $interval
     * @return int
     */
    public function getLowRange($minPriceArr, $interval)
    {
        $minPrice = floor($minPriceArr['price']);
        $lowerRange = $minPrice - ($minPrice % $interval);
        if ($lowerRange < 0) {
            $lowerRange = 0;
        }
        return $lowerRange;
    }

    /**
     * Get the nearest multiple of 10 higher than the max price in the list
     * @param array $maxPriceArr
     * @param int $interval
     * @return int
     */
    public function getHighRange($maxPriceArr, $interval)
    {
        $higherPriceAmt = floor($maxPriceArr['price']);
        $diff = $interval - ($higherPriceAmt % $interval);
        $highRange = $higherPriceAmt + $diff;
        return $highRange;
    }

    /**
     * Get all the prices of the product in the selected category
     * @return array
     */
    public function getPrices()
    {
        try {
            $pricesArray = $this->basysPricesResource->getPrices();
            $this->ranges = $this->calculateRanges($pricesArray);
            foreach ($pricesArray as $priceArr) {
                for ($i = 0; $i < $this->divisions; $i++) {
                    if (in_array(floor($priceArr['price']), range($this->ranges[$i]['lowLimit'], $this->ranges[$i]['highLimit']))) {
                        $this->ranges[$i]['count']++;
                    }
                }
            }
            return $this->ranges;
        } catch (\Exception $ex) {
            $this->logger->critical($ex->getMessage());
        }
    }

    /**
     * Create the ranges based on the minprice and maxprice
     * @param array $pricesArray
     * @return array
     */
    public function calculateRanges($pricesArray)
    {
        $interval = $this->getInterval(end($pricesArray));
        $lowLimit = $this->getLowRange($pricesArray[0], $interval);
        $highLimit = $this->getHighRange(end($pricesArray), $interval);

        $this->divisions = ($highLimit - $lowLimit)/$interval;
        if ($this->divisions<0) {
            $this->divisions = 1;
        }

        for ($i = 0; $i < $this->divisions; $i++) {
            $this->ranges[$i]['lowLimit'] = $lowLimit + ($i * $interval);
            $this->ranges[$i]['highLimit'] = $lowLimit + (($i+1) * $interval) - .01;
            $this->ranges[$i]['count'] = 0;
        }

        return $this->ranges;
    }
}
