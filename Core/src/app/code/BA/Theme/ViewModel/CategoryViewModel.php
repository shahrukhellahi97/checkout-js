<?php
namespace BA\Theme\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class CategoryViewModel implements ArgumentInterface
{
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    public function __construct(\Magento\Catalog\Helper\Category $categoryHelper)
    {
        $this->categoryHelper = $categoryHelper;
    }

    public function getFooterCategories()
    {
        $categories = $this->categoryHelper->getStoreCategories();
        $result = [];

        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($categories as $category) {
            $result[] = [
                'name' => $category->getName(),
                //'url' => $category->getUrl()
                'url' => $this->categoryHelper->getCategoryUrl($category)
            ];
        }

        return $result;
    }

    public function showFooter()
    {
        if ($this->store == 'michelin') {
            return false;
        }

        return true;
    }
}
