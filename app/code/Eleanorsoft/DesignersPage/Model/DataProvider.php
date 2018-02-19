<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eleanorsoft\DesignersPage\Model;

use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var \Eleanorsoft\DesignersPage\Model\ResourceModel\Designer\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct
    (
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blockCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $blockCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Eleanorsoft\DesignersPage\Model\Designer $item */
        foreach ($items as $item) {
            $dataForm = $item->getData();

            if (isset($dataForm['photo'])){
                $imageArr = [];
                $imageArr[0]['url'] = $item->getImageUrl($item->getPhoto());
                $dataForm['photo'] = $imageArr;
            }
            if (isset($dataForm['alternative_photo'])){
                $imageArr = [];
                $imageArr[0]['url'] = $item->getImageUrl($item->getAlternativePhoto());
                $dataForm['alternative_photo'] = $imageArr;
            }
            if (isset($dataForm['banner'])){
                $imageArr = [];
                $imageArr[0]['url'] = $item->getImageUrl($item->getBanner());
                $dataForm['banner'] = $imageArr;
            }


            $this->loadedData[$item->getId()] = $dataForm;

        }

        $data = $this->dataPersistor->get('designer_block');
        if (!empty($data)) {
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->loadedData[$block->getId()] = $block->getData();
            $this->dataPersistor->clear('designer_block');
        }

        return $this->loadedData;
    }
    public function asss()
    {
        $items = $this->collection->getItems();
        /** @var $image \Turiknox\SampleImageUploader\Model\Image */
        foreach ($items as $image) {
            $_data = $image->getData();
            if (isset($_data['image'])) {
                $imageArr = [];
                $imageArr[0]['name'] = 'Image';
                $imageArr[0]['url'] = $image->getImageUrl();
                $_data['image'] = $imageArr;
            }
            $image->setData($_data);
            $data[$image->getId()] = $_data;
        }
        return $data;
    }
}
