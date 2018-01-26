<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Controller\Adminhtml\Widget;

class Products extends \Magento\Widget\Controller\Adminhtml\Widget\Instance
{
    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Math\Random $mathRandom
     * @param \Magento\Framework\Translate\InlineInterface $translateInline
     * @param \Magento\Framework\View\Layout $layout
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Layout $layout
    ) {
        $this->layout = $layout;
        parent::__construct($context, $coreRegistry, $widgetFactory, $logger, $mathRandom, $translateInline);
    }

    /**
     * Categories chooser Action (Ajax request)
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $selected = $this->getRequest()->getParam('selected', '');
        $uniqID = $this->getRequest()->getParam('uniq_id', '');

        $chooser = $this->layout->createBlock('CleverSoft\CleverProduct\Block\Adminhtml\Catalog\Product\Widget\Chooser')
            ->setUseMassaction(true)
            ->setSelectedProducts(explode(',', $selected))
            ->setTemplate('widget/grid/extended.phtml')
        ;

        if(!empty($uniqID)) $chooser->setId($uniqID);

            /* @var $serializer Mage_Adminhtml_Block_Widget_Grid_Serializer */
        $serializer = $this->layout->createBlock('Magento\Backend\Block\Widget\Grid\Serializer');
        $serializer->setGridBlock($chooser)
                    ->setInputElementName('selected_products')
                    ->setReloadParamName('selected_products')
                    ->setSerializeData($chooser->getSelectedProducts());
        $this->setBody($chooser->toHtml().$serializer->toHtml());

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
        return $resultRaw->setContents($chooser->toHtml().$serializer->toHtml());
    }

}