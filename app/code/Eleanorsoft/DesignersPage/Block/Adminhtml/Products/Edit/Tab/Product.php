<?php
namespace Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Directory\Model\Currency;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Product
 *
 * @package Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit\Tab
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */
class Product extends Extended
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;


    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;


    public function __construct
    (
        Context $context,
        Data $backendHelper,
        Registry $_coreRegistry,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        $this->_coreRegistry = $_coreRegistry;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('designer_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * Get model designer
     *
     * @return mixed
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getItem()
    {
        return $this->_coreRegistry->registry('es_item');
    }

    /**
     * Column filter
     *
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'el_designer') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }


    /**
     * Create collection products
     *
     * @return $this
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    protected function _prepareCollection()
    {
        if ($this->getItem() && $this->getItem()->getId()) {
            $this->setDefaultFilter(['el_designer' => $this->getItem()->getId()]);
        }
        $collection = $this->collectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price');

        $storeId = (int)$this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $collection->addStoreFilter($storeId);
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Create columns for grid products
     *
     * @return $this
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    protected function _prepareColumns()
    {
        $this->addColumn('el_designer',
            [
                'type' => 'checkbox',
                'name' => 'el_designer',
                'values' => $this->_getSelectedProducts(),
                'index' => 'entity_id',
                'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction'
            ]
        );
        $this->addColumn('entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn('name',
            [
                'header' => __('Name'),
                'index' => 'name'
            ]
        );
        $this->addColumn('sku',
            [
                'header' => __('SKU'),
                'index' => 'sku'
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'currency_code' => (string)$this->_scopeConfig->getValue
                (
                    Currency::XML_PATH_CURRENCY_BASE,
                    ScopeInterface::SCOPE_STORE
                ),
                'index' => 'price'
            ]
        );
        return parent::_prepareColumns();
    }

    /**

     * @return string
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/products/grid', ['_current' => true]);
    }

    /**
     * Get saved values product
     *
     * @return array|null
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if ($products === null) {
            $products = array();
           if ($this->getItem()){
               $vProducts = $this->collectionFactory->create()
                   ->addAttributeToFilter('el_designer',  $this->getItem()->getId());

               foreach($vProducts as $pdct){
                   $products[]  = $pdct->getId();
               }
           }
        }
        return $products;
    }
}