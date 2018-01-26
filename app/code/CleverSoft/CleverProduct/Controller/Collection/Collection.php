<?php
/**
 * @category    CleverSoft
 * @package     CleverProduct
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverProduct\Controller\Collection;
use Magento\Framework\View\Result\PageFactory;
class Collection extends \Magento\Framework\App\Action\Action{

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    protected $_helperData;

    protected $productRepository;
    protected $resultPageFactory;

    protected $coreRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \CleverSoft\CleverProduct\Helper\Data $helperData,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Layout $layout
    ) {
        $this->layout = $layout;
        $this->productRepository = $productRepository;
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->_helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) return null;

        $type   = $this->getRequest()->getPost('type');
        $value  = $this->getRequest()->getPost('value');

        if (!$type && !$value) return null;

        $page = $this->resultPageFactory->create();
        $page->getConfig()->addBodyClass('page-collection-ajax');

        $limit      = $this->getRequest()->getPost('limit', 10);
        $carousel   = $this->getRequest()->getPost('carousel', 0);
        $carouselv   = $this->getRequest()->getPost('carouselv', 0);
        $column_ajax     = $this->getRequest()->getPost('column_ajax', 4);
        $column     = $this->getRequest()->getPost('column', 4);
        $row        = $this->getRequest()->getPost('row', 1);
        $cid        = $this->getRequest()->getPost('cid');
        $cpid        = $this->getRequest()->getPost('cpid');
        $layout        = $this->getRequest()->getPost('layout');
        $id        = $this->getRequest()->getPost('id');
        $template   = $this->getRequest()->getPost('template', 'widget/cases/tab_items.phtml');
        $lazyload   = $this->getRequest()->getPost('lazyload',true);
        $image_width   = $this->getRequest()->getPost('image_width',150);
        if ($image_width == "") $image_width = '220';
        $image_height   = $this->getRequest()->getPost('image_height',150);
        $height_image    = $this->getRequest()->getPost('height_image',150);
        $product_grid_style  = $this->getRequest()->getPost('product_grid_style')? $this->getRequest()->getPost('product_grid_style') : 'product_grid_style_1';
        $display_rating    = $this->getRequest()->getPost('display_rating') ? $this->getRequest()->getPost('display_rating') : 'visible';
        $display_addtocart    = $this->getRequest()->getPost('display_addtocart') ? $this->getRequest()->getPost('display_addtocart') : 'visible';
        $display_addtowishlist    = $this->getRequest()->getPost('display_addtowishlist') ? $this->getRequest()->getPost('display_addtowishlist') : 'visible';
        $display_addtocompare    = $this->getRequest()->getPost('display_addtocompare')? $this->getRequest()->getPost('display_addtocompare') : 'visible';
        $display_swatch_attributes    = $this->getRequest()->getPost('display_swatch_attributes')? $this->getRequest()->getPost('display_swatch_attributes') : 'visible';
        $display_productname    = $this->getRequest()->getPost('display_productname')? $this->getRequest()->getPost('display_productname') : 'visible';
        $display_name_single_line    = $this->getRequest()->getPost('display_name_single_line',true);
        $display_price    = $this->getRequest()->getPost('display_price')? $this->getRequest()->getPost('display_price') : 'visible';
        $countdown    = $this->getRequest()->getPost('countdown'); 

        /* @var $block CleverProduct_Block_Widget_Collection */
        $block = $this->layout->createBlock('CleverSoft\CleverProduct\Block\Widget\Collection');
        $params = array();

        if ($cid){
            $params['category_ids'] = explode(',', $cid);
        }

        if((bool) $countdown == 1  ) {
            $params['countdown_filter'] =1;
        }

        if($type == 'collections' && $cpid)
        {
            $product = $this->productRepository->getById($cpid);
            $this->coreRegistry->register('product', $product);
        }


        $block->setTemplate($template);
        $block->setData(array(
            'row'           => $row,
            'id'           => $id,
            'column_ajax'        => $column_ajax,
            'type'          => $type,
            'value'          => $value,
            'category_ids'   => $cid,
            'layout'   => $layout,
            'limit'   => $limit,
            'carousel'      => $carousel,
            'carouselv'      => $carouselv,
            'lazyload'      => $lazyload,
            'image_width'      => $image_width,
            'image_height'      => $image_height,
            'height_image'      => $height_image,
            'product_grid_style'      => $product_grid_style,
            'display_price'      => $display_price,
            'display_name_single_line'      => $display_name_single_line,
            'display_productname'      => $display_productname,
            'display_swatch_attributes'      => $display_swatch_attributes,
            'display_addtocompare'      => $display_addtocompare,
            'display_addtowishlist'      => $display_addtowishlist, 
            'display_addtocart'      => $display_addtocart,
            'display_rating'      => $display_rating,
            'countdown'      => $countdown,
            'collection'    => $this->_helperData->getProducts($type, $value, $params, $limit)
        ));

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);

        return $resultRaw->setContents($block->toHtml());
    }
}
