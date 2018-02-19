<?php
namespace Eleanorsoft\DesignersPage\Helper;

use Eleanorsoft\DesignersPage\Model\UploaderPool;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;

class Data extends AbstractHelper
{
    protected $uploaderPool;

    public function __construct
    (
        Context $context,
        UploaderPool $pool
    )
    {
        parent::__construct($context);

        $this->uploaderPool = $pool;
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
}