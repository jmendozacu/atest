<?php

namespace Eleanorsoft\DesignersPage\Block;

use CleverSoft\CleverProduct\Block\Widget as BaseWidget;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

class Widget extends BaseWidget implements BlockInterface
{

    /**
     *
     * @var Designer
     */
    protected $designer;

    /**
     * Widget constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \CleverSoft\CleverProduct\Helper\Data $helperData
     * @param Designer $designer
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \CleverSoft\CleverProduct\Helper\Data $helperData,
        Designer $designer,
        array $data = []
    )
    {
        parent::__construct($context, $jsonEncoder, $objectManager, $helperData, $data);
        $this->designer = $designer;
    }


    /**
     * Retrieve data about designer
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOptionDesigners()
    {
        $helper = $this->designer->getHelper();
        $designers = $this->designer->getCollection();
        $options = [];

        foreach ($designers as $designer){
            $options[] = array(
                'id' => $designer->getId(),
                'label' => $designer->getFullName(),
                'image' => $helper->getImageUrl($designer->getPhoto()),
                'altImage' => $helper->getImageUrl($designer->getAlternativePhoto()),
                'linkto' => $this->getUrl('designers/*/page', ['id' => $designer->getId()])
            );
        }
        return $options;
    }

    protected function _getProductCollection($type, $value){
        return $this->getOptionDesigners();
    }
}