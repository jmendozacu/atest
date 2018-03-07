<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Api\Data\DesignerInterface;
use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
use Eleanorsoft\DesignersPage\Helper\Data;
use Eleanorsoft\DesignersPage\Model\Designer as ModelDesigner;
use Eleanorsoft\DesignersPage\Model\UploaderPool;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Save extends Designer
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ModelDesigner
     */
    protected $model;

    /**
     * @var DesignerInterface
     */
    protected $designerInterface;

    /**
     * @var UploaderPool
     */
    protected $uploaderPool;

    /**
     * @var DataObjectHelper
     */
    protected $helper;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var Action
     */
    protected $action;

    /**
     * @var Data
     */
    protected $helperDesigner;

    public function __construct(
        Context $context,
        DesignerRepositoryInterface $repository,
        PageFactory $resultPageFactory,
        CollectionFactory $collectionFactory,
        DesignerInterface $designerInterface,
        DataPersistorInterface $dataPersistor,
        UploaderPool $uploaderPool,
        DataObjectHelper $helper,
        ProductRepositoryInterface $productRepository,
        Action $action,
        Data $helperDesigner,
        Json $json

    )
    {
        parent::__construct($context, $repository, $resultPageFactory, $collectionFactory);
        $this->designerInterface = $designerInterface;
        $this->dataPersistor = $dataPersistor;
        $this->uploaderPool = $uploaderPool;
        $this->helper = $helper;
        $this->productRepository = $productRepository;
        $this->action = $action;
        $this->helperDesigner = $helperDesigner;
        $this->json = $json;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $products = $data['designer_products'] ? $this->json->unserialize($data['designer_products']) : '';

        if ($data) {
            $id = $this->getRequest()->getParam('designer_id');

            if ($id) {
                $this->model = $this->repository->getById($id);
            } else {
                unset($data['designer_id']);
                $this->model = $this->designerInterface;
            }


            if (!$this->model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This designer no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            try {

                $photo = $this->getUploader('image')->uploadFileAndGetName('photo', $data);
                $alt = $this->getUploader('image')->uploadFileAndGetName('alternative_photo', $data);
                $banner = $this->getUploader('image')->uploadFileAndGetName('banner', $data);

                if ($this->model->getId()) {
                    if ($photo) {
                        $data['photo'] = $photo;
                    } else {
                        $data['photo'] = $this->model->getPhoto();
                    }
                    if ($alt) {
                        $data['alternative_photo'] = $alt;
                    } else {
                        $data['alternative_photo'] = $this->model->getAlternativePhoto();
                    }
                    if ($banner) {
                        $data['banner'] = $banner;
                    } else {
                        $data['banner'] = $this->model->getBanner();
                    }
                } else {
                    $data['photo'] = $photo;
                    $data['alternative_photo'] = $alt;
                    $data['banner'] = $banner;
                }


                $this->helper->populateWithArray($this->model, $data, DesignerInterface::class);

                $this->repository->save($this->model);

                $existingIds = $this->getProductIdsForDesigner($this->model->getId());
                $newIds = [];

                if ($products) {
                    $newIds = array_keys($products);
                }

                $removeIds = array_diff($existingIds, $newIds);
                $addIds = array_diff($newIds, $existingIds);

                $storeIds = $this->helperDesigner->toStoresArray();
                foreach ($storeIds as $id) {
                    $this->action->updateAttributes($removeIds, ['el_designer' => ''], $id);
                    $this->action->updateAttributes($addIds, ['el_designer' => $this->model->getId()], $id);
                }
                $ids = array_merge($removeIds, $addIds);
                $this->helperDesigner->reIndexering($ids);

                $this->messageManager->addSuccessMessage(__('You saved the designer.'));
                $this->dataPersistor->clear('designer_block');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['designer_id' => $this->model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the designer.'));
            }

            $this->dataPersistor->set('designer_block', $data);
            return $resultRedirect->setPath('*/*/edit', ['designer_id' => $this->getRequest()->getParam('designer_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $type
     * @return Uploader
     * @throws \Exception
     */
    protected function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }

}
