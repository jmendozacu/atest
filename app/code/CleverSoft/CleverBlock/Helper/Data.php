<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Helper;

use Magento\Customer\Model\Session as CustomerSession;

class Data extends \Magento\Framework\App\Helper\AbstractHelper{


    /**
     * Resource instance
     *
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $catalogProductVisibility;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Initialize dependencies
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;


    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $viewedModel;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_localeDate;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Reports\Model\Product\Index\Viewed $viewedModel,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        CustomerSession $customerSession
    ) {
        $this->_resource = $resource;
        $this->_customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->_coreRegistry = $registry;
        $this->_checkoutSession = $checkoutSession;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_catalogConfig = $catalogConfig;
        $this->_objectManager = $objectManager;
        $this->_localeDate = $localeDate;
        $this->viewedModel = $viewedModel;
        parent::__construct($context);
    }

    /**
     * Get theme's main settings (single option)
     *
     * @return string
     */
    public function getCfg($optionString, $store = null)
    {
        return $this->scopeConfig->getValue('cleversoftbase/' . $optionString);
    }

    public function getTable($name)
    {
        return $this->_resource->getTableName($name);
    }

    public function getResource()
    {
        return $this->_resource;
    }

    public function getRegistry()
    {
        return $this->_coreRegistry;
    }

    public function getModuleManager()
    {
        return $this->_moduleManager;
    }

    public function getCustomerSession()
    {
        return $this->_customerSession;
    }

    public function getStoreManager()
    {
        return $this->storeManager;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    /**
     * Get ids of products that are in cart
     *
     * @return array
     */
    public function getCartProductIds()
    {
        $ids = $this->_coreRegistry->registry('_cart_product_ids');
        if ($ids === null) {
            $ids = [];
            foreach ($this->_checkoutSession->getQuote()->getAllItems() as $item) {
                $product = $item->getProduct();
                if ($product) {
                    $ids[] = $product->getId();
                }
            }
            $this->_coreRegistry->register('_cart_product_ids', $ids);
        }
        return $ids;
    }

    /**
     * Prepare and return product collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function createCollection(array $params = [])
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter();

        if(isset($params['category_ids']))
        {
            $catsFilter = ['in' => $params['category_ids']];
            $collection->addCategoriesFilter($catsFilter);
        }

//        if(isset($params['category_ids']))
//        {
//            if (!is_array($params['category_ids'])) $categoryIds = array($params['category_ids']);
//            else $categoryIds = $params['category_ids'];
//
//            $fromPart = $collection->getSelect()->getPart('from');
//
//            if (isset($fromPart['cat_index'])){
//                $joinConditionStr = $fromPart['cat_index']['joinCondition'];
//                $joinConditions = explode(' AND ', $joinConditionStr);
//                foreach ($joinConditions as $index => $conditionStr){
//                    if (strpos($conditionStr, 'cat_index.category_id') !== false){
//                        $joinConditions[$index] = sprintf('cat_index.category_id IN (%s)', implode(',', $categoryIds));
//                    }
//                }
//                $fromPart['cat_index']['joinCondition'] = implode(' AND ', $joinConditions);
//                $collection->getSelect()->setPart('from', $fromPart);
//            }
//        }

        return $collection;
    }

    protected function _addProductAttributesAndPrices(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
    ) {
        return $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->addUrlRewrite();
    }


    public function getProducts($type = 'collections', $value = 'latest', $params, $limit=10){
        $collection = null;
        if (!is_array($params)) $params = array();
        switch ($type){
            case 'category':
                $collection = $this->_getProductCategoryCollection($value);
                break;
            case 'collections':
                switch ($value){
                    case 'latest':
                        $collection = $this->_getLatestCollection($params);
                        break;
                    case 'bestsell':
                        $collection = $this->_getBestSellerCollection($params);
                        break;
                    case 'new':
                        $collection = $this->_getNewCollection($params);
                        break;
                    case 'discount':
                        $collection = $this->_getDiscountCollection($params);
                        break;
                    case 'id':
                        $collection = $this->_getIdCollection($params);
                        break;
                    case 'related':
                        $collection = $this->_getRelatedCollection();
                        break;
                    case 'upsell':
                        $collection = $this->_getUpSellCollection($limit);
                        break;
                    case 'crosssell':
                        $collection = $this->_getCrossSellCollection();
                        break;
                    case 'mostviewed':
                        $collection = $this->_getMostViewedCollection($params);
                        break;
                    case 'rating':
                        $collection = $this->_getTopRatedCollection($params);
                        break;
                    case 'recentviewed':
                        $collection = $this->_getRecentViewedCollection($params);
                        break;
                    case 'random':
                    default:
                        $collection = $this->_getRandomCollection($params);
                        break;
                }
                break;
        }

        if ($collection){
            $collection->setPage(1, $limit);

            $this->_eventManager->dispatch(
                'catalog_block_product_list_collection',
                ['collection' => $collection]
            );
        }

        return $collection;
    }

    protected function _getProductCategoryCollection($category) {
        if (!$category) return null;

        if (!$category instanceof \Magento\Catalog\Model\Category){
            $categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category');
            $categoryModel = $categoryModel->load($category);
            if ($categoryModel->getId()){
                $params = array('category_ids' => $categoryModel->getId());
            }else{
                return null;
            }
        }else{
            $params = array('category_ids' => $category);
        }

        $collection = $this->createCollection($params);

        return $collection;
    }

    protected function _getLatestCollection($params) {
        $collection = $this->createCollection($params);

        $collection->setOrder('updated_at', 'desc');

        return $collection;
    }

    protected function _getBestSellerCollection($params) {
        $collection = $this->createCollection($params);

        /* @var $resource Mage_Core_Model_Resource */
        $orderItems = $this->getTable('sales_order_item');
        $orderMain  = $this->getTable('sales_order');
        $collection->getSelect()
            ->join(array('item' => $orderItems), 'item.product_id = e.entity_id', array('count' => 'SUM(item.qty_ordered)'))
            ->join(array('order' => $orderMain), 'item.order_id = order.entity_id', array())
            ->where('order.status = ?', 'complete')
            ->group('e.entity_id')
            ->order('count DESC');

        return $collection;
    }

    protected function _getNewCollection($params){

        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');

        $collection = $this->createCollection($params);
        $collection
            ->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayStartOfDayDate))
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_to_date', 'date' => true, 'from' => $todayStartOfDayDate),
                    array('attribute' => 'news_to_date', 'is'   => new \Zend_Db_Expr('null'))
                ),
                '',
                'left'
            )
            ->addAttributeToSort('news_from_date', 'desc');

        return $collection;
    }

    protected function _getDiscountCollection($params){
        $collection = $this->createCollection($params);

        $resource   = $this->getResource();
        $connection = $resource->getConnection('core_read');
        $websiteId  = $this->getStoreManager()->getStore(true)->getWebsite()->getId();
        /* @var $customerSession Mage_Customer_Model_Session */
        $customerSession = $this->getCustomerSession();
        $customerGroupId = $customerSession->getCustomerGroupId();

        $select = $connection->select()
            ->from($this->getTable('catalog_product_index_price'), array('entity_id'))
            ->where('price > final_price')
            ->where('website_id = ?', $websiteId)
            ->where('customer_group_id = ?', $customerGroupId);

        $collection->getSelect()->join(
            array('discount_idx' => $select),
            join(' AND ', array('discount_idx.entity_id = e.entity_id')),
            array()
        );
        unset($connection, $select);
        return $collection;
    }

    protected function _getIdCollection($params){
        if (isset($params['category_ids'])) unset($params['category_ids']);
        if (!isset($params['product_ids'])) return null;
        if (!is_array($params['product_ids'])) return null;
        if (!count($params['product_ids'])) return null;

        $collection = $this->createCollection($params);
        $collection->addIdFilter($params['product_ids']);

        return $collection;
    }

    protected function _getRelatedCollection(){
        /* @var $product Mage_Catalog_Model_Product */
        $product = $this->getRegistry()->registry('product');
        if (!$product) return null;
        $collection = $product->getRelatedProductCollection()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->setPositionOrder()
            ->addStoreFilter();

        if ($this->getModuleManager()->isEnabled('Magento_Checkout')){
            $ninProductIds = $this->getCartProductIds();
            if (!empty($ninProductIds)) {
                $collection->addExcludeProductFilter($ninProductIds);
            }
            $this->_addProductAttributesAndPrices($collection);
        }

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        foreach ($collection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $collection;
    }

    protected function _getUpSellCollection($limit=10){
        /* @var $product Mage_Catalog_Model_Product */
        $product = $this->getRegistry()->registry('product');
		
        if (!$product) return null;
		
        $collection = $product->getUpSellProductCollection()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->setPositionOrder()
            ->addStoreFilter();

        if ($this->getModuleManager()->isEnabled('Magento_Checkout')){
            $ninProductIds = $this->getCartProductIds();
            if (!empty($ninProductIds)) {
                $collection->addExcludeProductFilter($ninProductIds);
            }
            $this->_addProductAttributesAndPrices($collection);
        }
		
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        //$collection->setPage(1, $limit);
        //$collection->load();


        /**
         * Updating collection with desired items
         */
        /*$this->_eventManager->dispatch(
            'catalog_product_upsell',
            ['product' => $product, 'collection' => $collection, 'limit' => $limit]
        );*/

        foreach ($collection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $collection;
    }

    protected function _getCrossSellCollection(){
        /* @var $product Mage_Catalog_Model_Product */
        $product = $this->getRegistry()->registry('product');

        if (!$product) return null;

        $collection = $product->getCrossSellProductCollection()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->setPositionOrder()
            ->addStoreFilter();

        if ($this->getModuleManager()->isEnabled('Magento_Checkout')){
            $ninProductIds = $this->getCartProductIds();
            if (!empty($ninProductIds)) {
                $collection->addExcludeProductFilter($ninProductIds);
            }
            $this->_addProductAttributesAndPrices($collection);
        }

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());		

        foreach ($collection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $collection;
    }

    protected function _getMostViewedCollection($params) {

        $collection = $this->createCollection($params);

        $resource   = $this->getResource();
        $connection = $resource->getConnection('core_read');
        $storeId  = $this->getStoreManager()->getStore()->getId();

        $select = $connection->select()
            ->from(array('re' => $resource->getTableName('report_event')), array('object_id', 'views' => 'COUNT(re.event_id)'))
            ->join(array('ret' => $resource->getTableName('report_event_types')), 're.event_type_id = ret.event_type_id', array())
            ->where('ret.event_name = ?', 'catalog_product_view')
            ->where('re.store_id = ?', $storeId)
            ->group('re.object_id')
            ->order('views desc')
            ->having('views > ?', 0);

        $collection->getSelect()->join(
            array('view_idx' => $select),
            join(' AND ', array('view_idx.object_id = e.entity_id')),
            array()
        );

        unset($connection, $select);
        return $collection;
    }

    protected function _getRecentViewedCollection($params) {
        $collection = $this->viewedModel->getCollection()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes());

        if (isset($params['customer_id'])) {
            $collection->setCustomerId($params['customer_id']);
        }

        $collection->excludeProductIds($this->viewedModel->getExcludeProductIds())
            ->addUrlRewrite()
            ->setCurPage(1);


        /* Price data is added to consider item stock status using price index */
        $collection->addPriceData();

        /* Price data is added to consider item stock status using price index */
        $collection->addIndexFilter();
        $collection->setAddedAtOrder();

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        return $collection;
    }

    protected function _getTopRatedCollection($params){
        $collection = $this->createCollection($params);

        $resource   = $this->getResource();
        $connection = $resource->getConnection('core_read');
        $storeId    = $this->getStoreManager()->getStore()->getId();

        $select = $connection->select()
            ->from(
                $this->getTable('rating_option_vote_aggregated'),
                array('entity_pk_value', 'rating_total' => 'SUM(percent_approved)')
            )
            ->where('store_id = ?', $storeId)
            ->group('entity_pk_value')
            ->order('rating_total DESC');

        $collection->getSelect()->join(
            array('rating_idx' => $select),
            join(' AND ', array('rating_idx.entity_pk_value = e.entity_id')),
            array('rating_idx.rating_total')
        );
        unset($connection, $select);
        return $collection;
    }


    protected function _getRandomCollection($params){
        $collection = $this->createCollection($params);
        $collection->getSelect()->order('RAND()');

        return $collection;
    }


    public function getDate($product){
        $date = $product->getData('special_to_date');
        if ($date){
            $today = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
            if ($date > $today){
                return date('F j, Y', strtotime($date));
            }
        }
    }

    public function getImgBlog($content)
    {
        preg_match_all('/(?<!_)url=([\'"])?(.*?)\\1/', $content, $matches);
        if (isset($matches[2][0])) {
            return Mage::getBaseUrl('media') . $matches[2][0];
        } else {
            preg_match_all('/(?<!_)src=([\'"])?(.*?)\\1/', $content, $option);
            return $option[2][0];
        }
    }

    /**
     * Get image after cropping it
     * @param string $content
     * @param int $width
     * @param int $height
     * @param string $ratio
     * @return string
     * @throws
     */
    public function getCropImage($content, $width, $height, $ratio = '')
    {
        preg_match_all('/(?<!_)url=([\'"])?(.*?)\\1/', $content, $matches);
        if (isset($matches[2][0])) {
            $images = explode('/',$matches[2][0]);
            $sourceImage = Mage::getBaseDir('media') . DS . $matches[2][0];
            $image = new Varien_Image($sourceImage);

            list($widthImg, $heightImg, , ) = getimagesize($sourceImage);
            if($ratio == ''){
                $top = 0;
                $left = 0;
                $right = $widthImg - $width;
                $bottom = $heightImg - $height;
            }else{

                list($ratioWidth, $ratioHeight) = explode(':',$ratio);
                if($heightImg * ($ratioWidth / $ratioHeight) > $widthImg) {
                    $cropHeight = $widthImg / ($ratioWidth / $ratioHeight);
                    $cropWidth = $widthImg;
                }
                else {
                    $cropWidth = $heightImg * ($ratioWidth / $ratioHeight);
                    $cropHeight = $heightImg;
                }
                $top = 0;
                $left = 0;
                $right = $widthImg - $cropWidth;
                $bottom = $heightImg - $cropHeight;
            }

            $image->crop($top, $left, $right, $bottom);
            $image->save(Mage::getBaseDir('media'). DS . 'wysiwyg' . DS . 'crop' . DS . $images[1]);

            return Mage::getBaseUrl('media') .'/wysiwyg/crop/' . $images[1];
        } else {
            preg_match_all('/(?<!_)src=([\'"])?(.*?)\\1/', $content, $option);
            return $option[2][0];
        }
    }

    public function buildFilterUrl($rUrl, $cUrl)
    {
        $iPosition = strpos ($cUrl,'?');
        $cutUrl = substr($cUrl,$iPosition);

        return $rUrl.$cutUrl;
    }
}
