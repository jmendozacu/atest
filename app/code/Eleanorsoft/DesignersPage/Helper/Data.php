<?php
namespace Eleanorsoft\DesignersPage\Helper;

use Eleanorsoft\DesignersPage\Model\UploaderPool;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Indexer\Model\IndexerFactory;
use Magento\Indexer\Model\Indexer\CollectionFactory;
use Magento\Store\Model\StoreRepository;

class Data extends AbstractHelper
{
    /**
     * @var UploaderPool
     */
    protected $uploaderPool;

    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     *
     * @var CollectionFactory
     */
    protected $indexerCollectionFactory;

    /**
     *
     * @var IndexerFactory
     */
    protected $indexerFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param UploaderPool $pool
     * @param StoreRepository $storeRepository
     * @param CollectionFactory $indexerCollectionFactory
     * @param IndexerFactory $indexerFactory
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    public function __construct
    (
        Context $context,
        UploaderPool $pool,
        StoreRepository $storeRepository,
        CollectionFactory $indexerCollectionFactory,
        IndexerFactory $indexerFactory
    )
    {
        parent::__construct($context);
        $this->uploaderPool = $pool;
        $this->storeRepository = $storeRepository;
        $this->indexerCollectionFactory = $indexerCollectionFactory;
        $this->indexerFactory = $indexerFactory;
    }

    public function getImageUrl($image)
    {
        $url = false;
        if ($image) {
            if (is_string($image)) {
                $uploader = $this->uploaderPool->getUploader('image');
                $url = $uploader->getBaseUrl().$uploader->getBasePath().$image;

            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    public function toStoresArray()
    {
        $stores = $this->storeRepository->getList();
        $storeIds = [];

        foreach ($stores as $store) {
            $storeIds[] = $store["store_id"];
        }

        return $storeIds;
    }

    public function reIndexering($ids = array())
    {
        $idx = $this->indexerFactory->create();
        $idx->load('catalog_product_attribute');
        $idx->reindexList($ids);
    }
}