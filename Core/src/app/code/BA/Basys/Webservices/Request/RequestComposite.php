<?php
namespace BA\Basys\Webservices\Request;

use Magento\Framework\ObjectManager\TMapFactory;

class RequestComposite implements RequestBuilderInterface
{
    /**
     * @var \BA\Basys\Webservices\Request\RequestBuilderInterface[]|\Magento\Framework\ObjectManager\TMap
     */
    private $builders;

    public function __construct(
        TMapFactory $tmapFactory,
        array $builders = []
    ) {
        $this->builders = $tmapFactory->create(
            [
                'array' => $builders,
                'type' => RequestBuilderInterface::class
            ]
        );
    }

    public function build(array $arguments)
    {
        $result = [];
        
        foreach ($this->builders as $builder) {
            $result = $this->merge($result, $builder->build($arguments));
        }

        return $result;
    }

    protected function merge(array $result, array $builder)
    {
        return array_replace_recursive($result, $builder);
    }
}