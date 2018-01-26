<?php 
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Block\Adminhtml\Formfields\NewAction\Fields;
class SliderItems extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
	protected $_assetRepo;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = [])
    {
		$this->_assetRepo = $context->getAssetRepository();
        parent::__construct($context, $data);	
    }
	protected $_template = 'newaction/fields/slider_items.phtml';
	public function getElement()
    {
		$element = $this->_element;
        return $this->_element;
    }
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }
}
?>