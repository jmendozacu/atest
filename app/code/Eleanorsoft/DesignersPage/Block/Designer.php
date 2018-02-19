<?php
namespace Eleanorsoft\DesignersPage\Block;

use Eleanorsoft\DesignersPage\Model\Designer as ModelDesigner;
use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer\CollectionFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Designer

 * @package Eleanorsoft\DesignersPage\Block
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Designer extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var
     */
    protected $designerModel;

    public function __construct
    (
        Context $context,
        array $data = [],

        CollectionFactory $collectionFactory,
        ModelDesigner $designerModel
    )
    {
        parent::__construct($context, $data);

        $this->collectionFactory = $collectionFactory;
        $this->designerModel = $designerModel;
    }


    public function getDesigners()
    {
        return  $this->collectionFactory->create();
    }

    public function getDesignerById()
    {
        $id = $this->getRequest()->getParam('id');
        $collection = $this->collectionFactory->create();

        $collection->addFieldToFilter('designer_id', array("eq" => $id));

        return $collection;
    }

    public function getDataDesigner()
    {
        $collection = $this->getDesignerById();
        $data = [];

        foreach ($collection as $item){
            $data['full_name'] = $item->getFullName();
            $data['photo'] = $this->designerModel->getImageUrl($item->getPhoto());
            $data['alternative_photo'] = $this->designerModel->getImageUrl($item->getAlternativePhoto());
            $data['banner'] = $this->designerModel->getImageUrl($item->getBanner());
            $data['description'] = $item->getDescription();
            $data['product_ids'] = $item->getProductIds();
        }

        return $data;
    }

    public function getDataDesigners()
    {
        $collection = $this->getDesigners();
        $data = [];
        foreach ($collection as $item) {
            $data[$item->getFullName()] = $this->designerModel->getImageUrl($item->getPhoto());
        }

        return $data;
    }
}