<?php
namespace BA\BasysCatalog\Setup;

use BA\BasysCatalog\Model\Swatches;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \BA\BasysCatalog\Model\Swatches
     */
    protected $swatches;

    public function __construct(
        Swatches $swatches
    ) {
        $this->swatches = $swatches;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // $this->swatches->install();
    }
}
