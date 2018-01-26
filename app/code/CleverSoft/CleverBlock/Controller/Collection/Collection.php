<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverBlock\Controller\Collection;

class Collection extends \Magento\Framework\App\Action\Action{

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    protected $_helperData;
	
	protected $productRepository;

	protected $coreRegistry;
	
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \CleverSoft\CleverBlock\Helper\Data $helperData,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Layout $layout
    ) {
        $this->layout = $layout;
		$this->productRepository = $productRepository;
		$this->coreRegistry = $coreRegistry;
        $this->_helperData = $helperData;
        parent::__construct($context);
    }
    
    public function execute()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) return null;

        $type   = $this->getRequest()->getPost('type');
        $value  = $this->getRequest()->getPost('value');

        if (!$type && !$value) return null;

        $limit      = $this->getRequest()->getPost('limit', 10);
        $carousel   = $this->getRequest()->getPost('carousel', 0);
        $column     = $this->getRequest()->getPost('column', 4);
        $product_m_w     = $this->getRequest()->getPost('product_m_w', 220);
        $row        = $this->getRequest()->getPost('row', 1);
        $cid        = $this->getRequest()->getPost('cid');
		$cpid        = $this->getRequest()->getPost('cpid');
        $template   = $this->getRequest()->getPost('template', 'widget/product.phtml');

        /* @var $block CleverProduct_Block_Widget_Collection */
        $block = $this->layout->createBlock('CleverSoft\CleverBlock\Block\Widget\Collection');
        $params = array();

        if ($cid){
            $params['category_ids'] = explode(',', $cid);
        }
		
		if($type == 'collection' && $cpid)
		{
			$product = $this->productRepository->getById($cpid);
			$this->coreRegistry->register('product', $product);
		}
		

        $block->setTemplate($template);
        $block->setData(array(
            'row'           => $row,
            'column'        => $column,
            'product_m_w'        => $product_m_w,
            'type'          => $type,
            'value'          => $value,
            'category_ids'   => $cid,
            'carousel'      => $carousel,
            'collection'    => $this->_helperData->getProducts($type, $value, $params, $limit)
        ));

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);

        return $resultRaw->setContents($block->toHtml());
    }
}
