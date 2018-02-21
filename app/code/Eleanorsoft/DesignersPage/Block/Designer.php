<?php
namespace Eleanorsoft\DesignersPage\Block;

use Eleanorsoft\DesignersPage\Api\Data\DesignerInterface;
use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Helper\Data;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
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
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var DesignerInterface
     */
    protected $designerModel;

    /**
     * @var DesignerRepositoryInterface
     */
    protected $designerRepository;

    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var Product
     */
    protected $resourceProduct;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    public function __construct
    (
        Context $context,
        array $data = [],

        DesignerInterface $designerModel,
        Data $helper,
        DesignerRepositoryInterface $designerRepository,
        ProductInterface $product,
        Product $resourceProduct,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context, $data);

        $this->designerModel = $designerModel;
        $this->helper = $helper;
        $this->designerRepository = $designerRepository;
        $this->product = $product;
        $this->resourceProduct = $resourceProduct;
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * Get collection designers
     *
     * @return \Magento\Cms\Api\Data\BlockSearchResultsInterface
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getCollection()
    {
        return $this->designerRepository->getList(Collection::SORT_ORDER_ASC);
    }

    /**
     * Retrieve designer by id.
     *
     * @return \Magento\Cms\Api\Data\BlockInterface
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getDesignerById()
    {
        $id = $this->getRequest()->getParam('id');
        return $this->designerRepository->getById($id);
    }

    /**
     *Get Helper class
     *
     * @return Data
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Get designer for current product
     *
     * @return \Magento\Cms\Api\Data\BlockInterface
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getDesigner()
    {
        $id = $this->getRequest()->getParam('id');
        $this->resourceProduct->load($this->product, $id);

        $id_designer = $this->product->getData('el_designer');

        return $this->designerRepository->getById($id_designer);
    }

    public function getProducts()
    {
        $id = $this->getRequest()->getParam('id');

        $collection = $this->collectionFactory->create()
            ->addAttributeToFilter('el_designer', $id);

        return $collection;
    }
}