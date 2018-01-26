<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Controller\Adminhtml\Widget;

use Magento\Backend\App\Action;
class Attribute extends \Magento\Backend\App\Action{

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder
    ){
        $this->_jsonEncoder = $jsonEncoder;
        parent::__construct($context);
    }


    public function execute(){
        if ($this->getRequest()->isPost()){
            $values = explode(',', $this->getRequest()->getParam('value'));
            if (count($values) == 2){
                list($attributeId, $attributeCode) = $values;
                $optionCollection = $this->_objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection')
                    ->setAttributeFilter($attributeId)
                    ->setStoreFilter()
                    ->load();
                $options = array();
                foreach ($optionCollection as $option){
                    $options[] = array(
                        'id' => $option->getId(),
                        'label' => $option->getValue(),
                        'image' => $option->getImage()
                    );
                }
                $this->getResponse()->setBody($this->_jsonEncoder->encode($options));
            }
        }
    }
}