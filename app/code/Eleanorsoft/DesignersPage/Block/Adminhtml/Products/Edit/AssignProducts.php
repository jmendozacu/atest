<?php
namespace Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit;

use Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit\Tab\Product;
use Magento\Backend\Block\Template;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;


/**
 * Class AssignProducts
 *
 * @package Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class AssignProducts extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'assign_products.phtml';

    /**
     * @var Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    protected $json;

   public function __construct
   (
       Template\Context $context,

       Registry $registry,
       CollectionFactory $collectionFactory,
       Json $json,
       array $data = []
   )
   {
       parent::__construct($context, $data);

       $this->json = $json;
       $this->registry = $registry;
       $this->collectionFactory = $collectionFactory;
   }

    /**
     * Create block
     *
     * @return \Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit\Tab\Product
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     */
    public function getBlockGrid()
    {
        if ($this->blockGrid ===null) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Eleanorsoft\DesignersPage\Block\Adminhtml\Products\Edit\Tab\Product',
                'designer.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Create html block
     *
     * @return string
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * Serialize data
     *
     * @return bool|string
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function getProductsJson()
    {
        $products = array();

        if ($this->getItem()){
            $vProducts = $this->collectionFactory->create()
                ->addAttributeToFilter('el_designer',  $this->getItem()->getId());
            foreach($vProducts as $pdct){
                $products[$pdct->getId()]  = '';
            }
        }

        if (!empty($products)) {
            return $this->json->serialize($products);
        }
        return '{}';
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
        return $this->registry->registry('es_item');
    }
}