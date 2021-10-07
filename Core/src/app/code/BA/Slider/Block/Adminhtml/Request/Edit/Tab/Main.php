<?php
namespace BA\Slider\Block\Adminhtml\Request\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    protected $sessionData;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('request_request');

        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Main')]);

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $flag = false;
        if ($model->getImage()) {
            $flag = true;
        }

        $flagMobImage = false;
        if ($model->getMobimage()) {
            $flagMobImage = true;
        }

        $fieldset->addField(
            'website_id',
            'select',
            [
                'name' => 'website_id',
                'label' => __('Associate to Website'),
                'title' => __('Associate to Website'),
                'required' => true,
                'values' => $this->_systemStore->getWebsiteValuesForForm(),
            ]
        );

        $fieldset->addField(
            'enabled',
            'select',
            [
                'name' => 'enabled',
                'label' => __('Enable'),
                'title' => __('Enable'),
                'required' => true,
                'values' => ['1'=>'Yes','2'=>'No'],
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'caption',
            'text',
            [
                'name' => 'caption',
                'label' => __('Caption'),
                'title' => __('Caption'),
            ]
        );
        $fieldset->addField(
            'slider_price',
            'text',
            [
                'name' => 'slider_price',
                'label' => __('Price'),
                'title' => __('Price'),
            ]
        );
        $fieldset->addField(
            'button',
            'text',
            [
                'name' => 'button',
                'label' => __('Button'),
                'title' => __('Button'),
            ]
        );
        $fieldset->addField(
            'link',
            'text',
            [
                'name' => 'link',
                'label' => __('Link'),
                'title' => __('Link'),
            ]
        );
        $fieldset->addField(
            'description',
            'text',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'required' => true,
                'default' => '1'
            ]
        );

        if ($flag == true) {
            $fieldset->addField(
                'image',
                'image',
                [
                'name' => 'profile',
                'label' => __('Slider Image'),
                'title' => __('Slider Image'),
                'required'  => true,
                'class' => 'required-entry',
                'renderer'  => '\BA\Slider\Block\Adminhtml\Request\Renderer\TeamImage',
                ]
            )
            ->setAfterElementHtml('
            <script>
                require([
                    "jquery",
                ], function($){
                    $(document).ready(function () {
                        if($("#page_image").attr("value")){
                            $("#page_image").removeClass("required-file");
                        }else{
                            $("#page_image").addClass("required-file");
                        }
                        $( "#page_image" ).attr( "accept", "image/x-png,image/gif,image/jpeg,image/jpg,image/png" );
                    });
                });
            </script>
            ');
        } else {
            $fieldset->addField(
                'image',
                'image',
                [
                'name' => 'profile',
                'label' => __('Slider Image'),
                'title' => __('Slider Image'),
                'required'  => true,
                'class' => 'required-entry',
                'note' => 'Allow image type: jpg, jpeg, png, gif'
                ]
            )
            ->setAfterElementHtml('
            <script>
                require([
                    "jquery",
                ], function($){
                    $(document).ready(function () {
                        if($("#page_image").attr("value")){
                            $("#page_image").removeClass("required-file");
                        }else{
                            $("#page_image").addClass("required-file");
                        }
                        $( "#page_image" ).attr( "accept", "image/x-png,image/gif,image/jpeg,image/jpg,image/png" );
                    });
                });
            </script>
            ');
        }

        if ($flagMobImage == true) {
            $fieldset->addField(
                'mobimage',
                'image',
                [
                'name' => 'mobimage',
                'label' => __('Mob Image'),
                'title' => __('Mob Image'),
                'required'  => true,
                'class' => 'required-entry',
                'renderer'  => '\BA\Slider\Block\Adminhtml\Request\Renderer\TeamImage',
                ]
            )
            ->setAfterElementHtml('
            <script>
                require([
                    "jquery",
                ], function($){
                    $(document).ready(function () {
                        if($("#page_mobimage").attr("value")){
                            $("#page_mobimage").removeClass("required-file");
                        }else{
                            $("#page_mobimage").addClass("required-file");
                        }
                        $("#page_mobimage").attr( "accept","image/x-png,image/gif,image/jpeg,image/jpg,image/png" );
                    });
                });
            </script>
            ');
        } else {
            $fieldset->addField(
                'mobimage',
                'image',
                [
                'name' => 'mobimage',
                'label' => __('Mob Image'),
                'title' => __('Mob Image'),
                'required'  => true,
                'class' => 'required-entry',
                'note' => 'Allow image type: jpg, jpeg, png, gif'
                ]
            )
            ->setAfterElementHtml('
            <script>
                require([
                    "jquery",
                ], function($){
                    $(document).ready(function () {
                        if($("#page_mobimage").attr("value")){
                            $("#page_mobimage").removeClass("required-file");
                        }else{
                            $("#page_mobimage").addClass("required-file");
                        }
                        $( "#page_mobimage" ).attr( "accept", "image/x-png,image/gif,image/jpeg,image/jpg,image/png" );
                    });
                });
            </script>
            ');
        }

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Main');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Main');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
