<?php

namespace Eleanorsoft\DesignersPage\Model\Config\Source;

use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Options extends AbstractSource
{

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    protected $_options;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Retrieve All designers
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options[] = ['label'=>'Select Desiner', 'value'=>''];;

        $collection = $this->collectionFactory->create();
        foreach ($collection as $item){
            $id = $item->getId();
            $full_name = $item->getFullName();

            $this->_options[] = ['label' => $full_name, 'value' => $id];
        }

        return $this->_options;
    }
}