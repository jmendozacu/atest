<?php

namespace Eleanorsoft\DesignersPage\Block;

use CleverSoft\CleverBrands\Block\Widget\Adminhtml\Brands;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Widget extends Brands
{
    /**
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     *
     * @var Repository
     */
    protected $productAttributeRepository;

    /**
     *
     * @var Designer
     */
    protected $designer;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Repository $productAttributeRepository,
        Designer $designer,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->storeManager = $storeManager;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->designer = $designer;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/designers.phtml');
    }

    public function getOptionBrands()
    {
        $currentStore = $this->storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        $helper = $this->designer->getHelper();
        $designers = $this->designer->getCollection();

        $options = array();

        foreach ($designers as $designer){
            $options[] = array(
                'label' => $designer->getFullName(),
                'image' => $helper->getImageUrl($designer->getPhoto()),
                'linkto' => $this->getUrl('designers/*/page', ['id' => $designer->getId()])
            );
        }
        return $options;
    }
}