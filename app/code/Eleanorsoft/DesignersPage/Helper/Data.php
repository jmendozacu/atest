<?php
namespace Eleanorsoft\DesignersPage\Helper;

use Eleanorsoft\DesignersPage\Model\UploaderPool;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
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

    public function __construct
    (
        Context $context,
        UploaderPool $pool,
        StoreRepository $storeRepository
    )
    {
        parent::__construct($context);

        $this->uploaderPool = $pool;
        $this->storeRepository = $storeRepository;
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
}