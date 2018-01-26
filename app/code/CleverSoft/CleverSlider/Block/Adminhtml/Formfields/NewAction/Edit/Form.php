<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Block\Adminhtml\Formfields\NewAction\Edit;
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_yesnoFactory;
    protected $_layoutType;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Config\Model\Config\Source\YesnoFactory $yesnoFactory,
        \CleverSoft\CleverSlider\Model\LayoutFactory $layoutType,
        array $data = []
    ) {
        $this->_yesnoFactory = $yesnoFactory;
        $this->_systemStore = $systemStore;
        $this->_layoutType = $layoutType;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->setId('slideshow_form');
        $this->setTitle(__('Slider Information'));
    }
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('slideshow');
        $yesno = $this->_yesnoFactory->create()->toOptionArray();
        $layout_type = $this->_layoutType->create()->toOptionArray();
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );
        $form->setHtmlIdPrefix('menu_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Settings'), 'class' => 'fieldset-wide']
        );
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );
        $fieldset->addField(
            'identifier',
            'text',
            ['name' => 'identifier', 'label' => __('Identifier'), 'title' => __('Identifier'), 'required' => true]
        );

        $fieldset->addField(
            'css_class',
            'text',
            [
                'label' => __('Wrapper CSS Class'),
                'title' => __('Wrapper CSS Class'),
                'name' => 'css_class',
                'required' => false
            ]
        );

        $fieldset->addField(
            'layout',
            'select',
            [
                'name' => 'layout',
                'label' => __('Layout'),
                'title' => __('Layout'),
                'values' =>  $layout_type,
            ]
        );

        $fieldset->addField(
            'auto_rotate',
            'select',
            [
                'name' => 'auto_rotate',
                'label' => __('Enable auto rotate'),
                'title' => __('Enable auto rotate'),
                'values' =>  $yesno,
            ]
        );

        $fieldset->addField(
            'slide_animation',
            'select',
            [
                'name' => 'slide_animation',
                'label' => __('Change slides every'),
                'title' => __('Change slides every'),
                'values' => array('5000'=>'5 Seconds','6000' => '6 Seconds','7000' => '7 Seconds', '8000' => '8 Seconds', '9000' => '9 Seconds', '10000' => '10 Seconds',)
            ]
        );

        $fieldset->addField(
            'slide_height',
            'text',
            array(
                'name' => 'slide_height',
                'label' => __('Section height (Auto and Full Width layout)'),
                'title' => __('Section height'),
                'placeholder' => '600'
            )
        );

        if(!$model->getId()){
            $model->setData('single_slide',1);
        }
        $s_checked = $model->getData('single_slide');
        $html = '<div class="admin__scope-old">
			<div class="product-actions">
				<div class="switcher" onselectstart="return false;" style="float:left; margin-top: 7px;">
					<input type="checkbox" id="switch_single_slide" '.( ($s_checked)?'checked':'' ).'>
					<label class="switcher-label" for="switch_single_slide" data-text-on="'.__('Enabled').'" data-text-off="'.__('Disabled').'" title="Product online status"></label>
				</div>
			</div>
		</div>';
        $fieldset->addField(
            'single_slide',
            'text',
            [
                'style' => 'display:none',
                'label' => __('Single item per slide'),
                'title' => __('Single item per slide'),
                'name' => 'single_slide',
                'required' => true,
            ]
        )->setBeforeElementHtml(
            $html
        );

        $fieldset->addField(
            'number_item_480',
            'text',
            array(
                'name' => 'number_item_480',
                'label' => __('Number Items (480px up)'),
                'title' => __('Number Items (480px up)'),
                'placeholder' => '1'
            )
        );
        $fieldset->addField(
            'number_item_768',
            'text',
            array(
                'name' => 'number_item_768',
                'label' => __('Number Items (768px up)'),
                'title' => __('Number Items (768px up)'),
                'placeholder' => '2 '
            )
        );
        $fieldset->addField(
            'number_item_992',
            'text',
            array(
                'name' => 'number_item_992',
                'label' => __('Number Items (992px up)'),
                'title' => __('Number Items (992px up)'),
                'placeholder' => '3'
            )
        );
        $fieldset->addField(
            'number_item_1200',
            'text',
            array(
                'name' => 'number_item_1200',
                'label' => __('Number Items (1200px up)'),
                'title' => __('Number Items (1200px up)'),
                'placeholder' => '4'
            )
        );

        $fieldset->addField(
            'show_nav',
            'select',
            [
                'name' => 'show_nav',
                'label' => __('Show next/prev button'),
                'title' => __('Show next/prev button'),
                'values' =>  $yesno,
            ]
        );
        $fieldset->addField(
            'show_pagination',
            'select',
            [
                'name' => 'show_pagination',
                'label' => __('Show pagination'),
                'title' => __('Show pagination'),
                'values' =>  $yesno,
            ]
        );

        if($style = $model->getData('style')){
            $style = json_decode($style);
            $model->setData('css_class',$style->css_class);
        }
        if(!$model->getId()){
            $model->setData('is_active',1);
        }
        $checked = $model->getData('is_active');

        $html = '<div class="admin__scope-old">
			<div class="product-actions">
				<div class="switcher" onselectstart="return false;" style="float:left; margin-top: 7px;">
					<input type="checkbox" onchange="document.getElementById(\'menu_is_active\').value = (this.checked)?1:0;" id="switch_is_active" '.( ($checked)?'checked':'' ).'>
					<label class="switcher-label" for="switch_is_active" data-text-on="'.__('Enabled').'" data-text-off="'.__('Disabled').'" title="Product online status"></label>
				</div>
			</div>
		</div>';
        $fieldset->addField(
            'is_active',
            'text',
            [
                'style' => 'display:none',
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
            ]
        )->setBeforeElementHtml(
            $html
        );

        $renderer = $this->getLayout()->createBlock(
            'CleverSoft\CleverSlider\Block\Adminhtml\Formfields\NewAction\Fields\SliderItems'
        );
        $field = $fieldset->addField('content', 'hidden', ['name' => 'content', 'label'=>'Slider Content']);
        $field->setRenderer($renderer);


        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}