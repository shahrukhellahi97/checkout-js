<?php
namespace BA\UserType\Ui\Component\Config\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    const URL_PATH = 'ba_usertype/config';

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
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->getUrl('/edit', $item['config_id']),
                        'label' => __('Edit')
                    ],
                    'remove' => [
                        'href' => $this->getUrl('/remove', $item['config_id']),
                        'label' => __('Remove')
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