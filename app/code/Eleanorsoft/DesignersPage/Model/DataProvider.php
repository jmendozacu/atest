<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eleanorsoft\DesignersPage\Model;

use Eleanorsoft\DesignersPage\Helper\Data;
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
     * @var Data
     */
    protected $helper;

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
        Data $helper,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $blockCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->helper = $helper;
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
                $imageArr[0]['url'] = $this->helper->getImageUrl($item->getPhoto());
                $dataForm['photo'] = $imageArr;
            }
            if (isset($dataForm['alternative_photo'])){
                $imageArr = [];
                $imageArr[0]['url'] = $this->helper->getImageUrl($item->getAlternativePhoto());
                $dataForm['alternative_photo'] = $imageArr;
            }
            if (isset($dataForm['banner'])){
                $imageArr = [];
                $imageArr[0]['url'] = $this->helper->getImageUrl($item->getBanner());
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
}
