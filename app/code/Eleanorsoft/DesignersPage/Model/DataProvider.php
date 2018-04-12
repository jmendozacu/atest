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
     *
     * @var ResourceModel\Designer
     */
    private $resourcemodel;

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
        \Eleanorsoft\DesignersPage\Model\ResourceModel\Designer $resourcemodel,
        Data $helper,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->resourcemodel = $resourcemodel;
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
            $designer_id = $dataForm['designer_id'];
            $dataDesigner = $this->resourcemodel->getDataDesigner($designer_id);
            foreach ($dataDesigner as $key=>$value) {
                $dataForm[$key] = $value;
            }

            if ($dataForm['photo'] != ''){
                $imageArr = [];
                $imageArr[0]['url'] = $this->helper->getImageUrl($dataForm['photo']);
                $dataForm['photo'] = $imageArr;
            }
            if ($dataForm['alternative_photo'] != ''){
                $imageArr = [];
                $imageArr[0]['url'] = $this->helper->getImageUrl($dataForm['alternative_photo']);
                $dataForm['alternative_photo'] = $imageArr;
            }
            if ($dataForm['banner'] != ''){
                $imageArr = [];
                $imageArr[0]['url'] = $this->helper->getImageUrl($dataForm['banner']);
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
