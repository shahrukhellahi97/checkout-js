<?php
namespace BA\BasysCatalog\Setup\Patch\Data;

use BA\BasysCatalog\Model\Swatches;
use BA\BasysCatalog\Setup\Patch\AbstractAttribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddColourAttributes extends AbstractAttribute
{
    /**
     * @var \BA\BasysCatalog\Model\Swatches
     */
    protected $swatches;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        Swatches $swatches
    ) {
        parent::__construct($moduleDataSetup, $eavSetupFactory);

        $this->swatches = $swatches;
    }

    public function apply()
    {
        // $this->swatches->install();        
    }

    public function revert()
    {
    }
}