<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Block\Adminhtml\Formfields;
class NewAction extends \Magento\Backend\Block\Widget\Form\Container{
	protected $_coreRegistry = null;
	public function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		\Magento\Framework\Registry $registry,
		array $data = []
	){
		$this->_coreRegistry = $registry;
		parent::__construct($context, $data);
	}
	protected function _construct()
	{
		$this->_objectId = 'id';
        $this->_blockGroup = 'CleverSoft_CleverSlider';
        $this->_controller = 'adminhtml_formfields_newAction';
		
		parent::_construct();

		$this->buttonList->update('save', 'label', __('Save Slider'));
		if ($this->_coreRegistry->registry('slideshow')->getId()) {
			$this->buttonList->add(
				'duplicate',
				[
					'label' => __('Duplicate'),
					'data_attribute' => [
						'mage-init' => [
							'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form', 'event_data' => ['type' => 'duplicate']],
						],
					]
				]
			);
		}
		$this->buttonList->add(
			'saveandcontinue',
			[
				'label' => __('Save and Continue Edit'),
				'class' => 'save',
				'data_attribute' => [
					'mage-init' => [
						'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
					],
				]
			],
			-100
		);

//		$this->buttonList->update('back','onclick',$this->buttonList->);

		$this->buttonList->remove('reset');
		$this->buttonList->update('delete', 'label', __('Delete'));
	}

	public function getBackUrl() {
		return $this->getUrl('*/*/manager');
	}

	public function getHeaderText()
	{
		if ($this->_coreRegistry->registry('slideshow')->getId()) {
			return __("Edit Slider '%1'", $this->escapeHtml($this->_coreRegistry->registry('slideshow')->getTitle()));
		} else {
			return __('New Slider');
		}
	}
	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}
	protected function _getSaveAndContinueUrl()
	{
		return $this->getUrl('slideshow/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
	}
	
}