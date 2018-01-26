<?php
/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\Base\Block;


use Symfony\Component\Config\Definition\Exception\Exception;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Page\Config;

class Template extends \Magento\Framework\View\Element\Template
{
    public $_coreRegistry;

    protected $_blockCollection;
    protected $_pageConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $blockCollection,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_blockCollection = $blockCollection;
        $this->_pageConfig = $context->getPageConfig();
        parent::__construct($context, $data);
        $this->addBodyClassLayout();
    }

    public function getConfig($config_path, $storeCode = null)
    {
        return $this->_scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function addBodyClassLayout() {
        $pageLayout = $this->getConfig('cleversoftbase/responsive/page_layout');
        $maxWidthpage = $this->getConfig('cleversoftbase/responsive/page_maxi_width');
        $rtl_language = $this->getConfig('cleversoftbase/responsive/rtl_language');
        if ($pageLayout) {
            $this->_pageConfig->addBodyClass($pageLayout);
        }
        if ($maxWidthpage) {
            $this->_pageConfig->addBodyClass('layout_'.$maxWidthpage);
        }

        if ($rtl_language) {
            $this->_pageConfig->addBodyClass('rtl');
        }
    }

    public function cssTemplate($content,$enable) {
        if (!$enable || empty($content)) return '';
        else $content = str_replace(PHP_EOL, '', $content);
        return <<<HTML
<style>
$content;
</style>
HTML;
    }

    public function getBlocks(){
        $blocks     = [];
        $layout     = $this->getLayout();
        $storeId    = $this->_storeManager->getStore()->getId();

        $classes    = [];
        $order      = [];


        foreach (array('lg', 'md', 'sm', 'xs') as $l){
            if (count(explode('|', $this->getConfig('cleversoftbase/footer/block_' . $l)) > 0) && $this->getConfig('cleversoftbase/footer/block_' . $l)) {
                foreach (explode('|', $this->getConfig('cleversoftbase/footer/block_' . $l)) as $block) {
                    list($blockId, $column, $cls) = explode(',', $block);

                    if (!isset($classes[$blockId])) {
                        $classes[$blockId] = "col-{$l}-{$column} ";
                    } else {
                        $classes[$blockId] .= "col-{$l}-{$column} ";
                    }
                    $classes[$blockId] .= "{$cls} ";

                    if (!in_array($blockId, $order)) {
                        $order[] = $blockId;
                    }
                }
            }
        }

        foreach ($order as $blockId){
            /* @var $collection Mage_Cms_Model_Resource_Block_Collection */
            $collection = $this->_blockCollection->create()->addFieldToFilter('block_id', array('eq' => $blockId));

            if ($collection->count()){
                /* @var $block Mage_Cms_Model_Block */
                $block = $collection->getFirstItem();
                $block->load($block->getId());
                $storeIds = $block->getStoreId();
                if ($block->getIsActive() && (in_array($storeId, $storeIds) || in_array(0, $storeIds))){
                    $blocks[] = array(
                        'class'     => isset($classes[$blockId]) ? $classes[$blockId] : '',
                        'content'   => $layout->createBlock('Magento\Cms\Block\Block')->setStoreId()->setBlockId($blockId)->toHtml()
                    );
                }
            }
        }

        return $blocks;
    }

    public function renderCollection($template='header.phtml'){
        /* @var $block CleverProduct_Block_Widget_Collection */
        $this->setData(array(
            'full_header_width'            => $this->getConfig('cleversoftbase/header/full_header_width'),
            'compare'            => $this->getConfig('cleversoftbase/header/compare'),
            'wishlist'            => $this->getConfig('cleversoftbase/header/wishlist')
        ));
        $this->setTemplate($template);

        return $this->toHtml();
    }

    public function isHomePage()
    {
        $currentUrl = $this->getUrl('', ['_current' => true]);
        $urlRewrite = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        return $currentUrl == $urlRewrite;
    }

    public function getMediaUrl(){
        return $this->_urlBuilder
            ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]);
    }
}