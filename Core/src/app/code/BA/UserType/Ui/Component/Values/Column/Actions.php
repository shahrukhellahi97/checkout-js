<?php
namespace BA\UserType\Ui\Component\Values\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    const URL_PATH = 'ba_usertype/values';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                // here we can also use the data from $item to configure some parameters of an action URL
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->getUrl('/edit', $item['list_id']),
                        'label' => __('Edit')
                    ],
                    'remove' => [
                        'href' => $this->getUrl('/remove', $item['list_id']),
                        'label' => __('Remove')
                    ],
                    'duplicate' => [
                        'href' => $this->getUrl('/duplicate', $item['list_id']),
                        'label' => __('Duplicate')
                    ],
                ];
            }
        }

        return $dataSource;
    }

    private function getUrl($path, $listId)
    {
        return $this->urlBuilder->getUrl(
            self::URL_PATH . $path,
            ['id' => (int) $listId]
        );
    }
}