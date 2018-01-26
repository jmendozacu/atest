<?php
/**
 * @category    CleverSoft
 * @package     CleverBrands
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

/**
 * Product Chooser for "Product Link" Cms Widget Plugin
 */
namespace CleverSoft\CleverBrands\Block\Widget;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

/**
 * Class Conditions
 */
class Brands extends Template implements RendererInterface
{
    /**
     * @var \Magento\Rule\Block\Conditions
     */
    protected $conditions;

    /**
     * @var \Magento\CatalogWidget\Model\Rule
     */
    protected $rule;

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $elementFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var AbstractElement
     */
    protected $element;

    /**
     * @var \Magento\Framework\Data\Form\Element\Text
     */
    protected $input;

    /**
     * @var string
     */
    protected $_template = 'product/widget/conditions.phtml';

    protected $_productAttributeRepository;
    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magento\Rule\Block\Conditions $conditions
     * @param \Magento\CatalogWidget\Model\Rule $rule
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Magento\Rule\Block\Conditions $conditions,
        \Magento\CatalogWidget\Model\Rule $rule,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        $this->conditions = $conditions;
        $this->rule = $rule;
        $this->registry = $registry;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_productAttributeRepository =  $objectManager->create('Magento\Catalog\Model\Product\Attribute\Repository');
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $widget = $this->registry->registry('current_widget_instance');
        if ($widget) {
            $widgetParameters = $widget->getWidgetParameters();
            if (isset($widgetParameters['conditions'])) {
                $this->rule->loadPost($widgetParameters);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render(AbstractElement $element)
    {
        $this->element = $element;
        return $this->toHtml();
    }

    /**
     * @return AbstractElement
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return string
     */
    public function getHtmlId()
    {
        return $this->getElement()->getContainer()->getHtmlId();
    }

    /**
     * @return string
     */
    public function getInputHtml()
    {
        $widget = $this->registry->registry('current_widget_instance');
        if ($widget) {
            return $widget->getWidgetParameters();
        } else {
            $json_value =  $this->getRequest()->getParam('widget');
            if($json_value) {
                $data = json_decode($json_value, true);
                return $data['values'];
            }
        }
    }

    public function getAttibuteOptions(){
        $manufacturerOptions = $this->_productAttributeRepository->get('manufacturer')->getOptions();

        $options = array();
        foreach ($manufacturerOptions as $manufacturerOption) {
            if($manufacturerOption->getLabel() == " "){ continue; }
            $options[] = array(
                'label' => $manufacturerOption->getLabel(),
                'value' => $manufacturerOption->getValue()
            );
        }

        return $options;
    }
}
