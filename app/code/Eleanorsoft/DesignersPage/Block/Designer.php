<?php
namespace Eleanorsoft\DesignersPage\Block;

use Eleanorsoft\DesignersPage\Api\Data\DesignerInterface;
use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Helper\Data;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Framework\Data\Collection;
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


    protected $helper;
    protected $designerModel;
    protected $designerRepository;
    protected $product;
    protected $resourceProduct;

    public function __construct
    (
        Context $context,
        array $data = [],

        DesignerInterface $designerModel,
        Data $helper,
        DesignerRepositoryInterface $designerRepository,
        ProductInterface $product,
        Product $resourceProduct
    )
    {
        parent::__construct($context, $data);

        $this->designerModel = $designerModel;
        $this->helper = $helper;
        $this->designerRepository = $designerRepository;
        $this->product = $product;
        $this->resourceProduct = $resourceProduct;
    }


    public function getCollection()
    {
        return $this->designerRepository->getList(Collection::SORT_ORDER_ASC);
    }

    public function getDesignerById()
    {
        $id = $this->getRequest()->getParam('id');
        return $this->designerRepository->getById($id);
    }

    public function getHelper()
    {
        return $this->helper;
    }

    public function getDesigner()
    {
        $id = $this->getRequest()->getParam('id');
        $this->resourceProduct->load($this->product, $id);

        $id_designer = $this->product->getData('el_designer');

        return $this->designerRepository->getById($id_designer);

    }
}