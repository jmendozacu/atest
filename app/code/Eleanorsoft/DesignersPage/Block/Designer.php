<?php
namespace Eleanorsoft\DesignersPage\Block;

use Eleanorsoft\DesignersPage\Api\Data\DesignerInterface;
use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Helper\Data;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Helper\Stock;
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

    /**
     * @var Stock
     */
    protected $stock;

    public function __construct(
        Context $context,

        DesignerInterface $designerModel,
        Data $helper,
        DesignerRepositoryInterface $designerRepository,
        ProductInterface $product,
        Product $resourceProduct,
        CollectionFactory $collectionFactory,
        Stock $stock,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->designerModel = $designerModel;
        $this->helper = $helper;
        $this->designerRepository = $designerRepository;
        $this->product = $product;
        $this->resourceProduct = $resourceProduct;
        $this->collectionFactory = $collectionFactory;
        $this->stock = $stock;
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

        if (!$id_designer) {
            return false;
        }
        return $this->designerRepository->getById($id_designer);
    }

    public function getProducts($designer_id)
    {
        $store_id = $this->_storeManager->getStore()->getId();

        $collection = $this->collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('el_designer', $designer_id)
            ->addAttributeToFilter('status', ['eq' => Status::STATUS_ENABLED])
            ->addAttributeToFilter('visibility', ['neq' =>Visibility::VISIBILITY_NOT_VISIBLE])
            ->addStoreFilter($store_id);

        $this->stock->addInStockFilterToCollection($collection);

        return $collection;
    }

    public function getProductListHtml()
    {

        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Catalog\Block\Product\ListProduct $block */
        $block = $this->getChildBlock('product_list_designer');

        $block->setCollection($this->getProducts($id));

        return $block->toHtml();
    }
}