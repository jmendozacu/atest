<?php

namespace Eleanorsoft\DesignersPage\Model;

use Eleanorsoft\DesignersPage\Api\Data\DesignerInterface;
use Eleanorsoft\DesignersPage\Api\Data\DesignerTranslatorInterface;
use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer as ResourceDesigner;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Model\Context;


/**
 * Class Designer
 * todo: What is its purpose? What does it do?
 *
 * @package Eleanorsoft\DesignersPage\Model
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Designer extends AbstractModel implements DesignerInterface, DesignerTranslatorInterface
{

    /**
     * @var UploaderPool
     */
    protected $uploaderPool;

    /**
     * designer translation
     */
    protected $translation;

    public function __construct
    (
        Context $context,
        Registry $registry,
        UploaderPool $pool,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->uploaderPool = $pool;
    }

    protected function _construct()
    {
        $this->_init(ResourceDesigner::class);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::DESIGNER_ID);
    }

    /**
     * Set ID
     *
     * @param $value
     * @return DesignerInterface
     * @internal param int $id
     */
    public function setId($value)
    {
        return $this->setData(self::DESIGNER_ID, $value);
    }

    /**
     * Get Full Name
     *
     * @return string|null
     */
    public function getFullName()
    {
        return $this->translation[self::FULL_NAME];
    }

    /**
     * Set Full Name
     *
     * @param $value
     * @return null|string
     * @internal param $fullName
     */
    public function setFullName($value)
    {
        return $this->translation[self::FULL_NAME] = $value;
    }

    /**
     * Get photo
     *
     * @return string|null
     */
    public function getPhoto()
    {
        return $this->translation[self::PHOTO];
    }

    /**
     * Set photo
     *
     * @param $value
     * @return null|string
     */
    public function setPhoto($value)
    {
        return $this->translation[self::PHOTO] = $value;
    }

    /**
     * Get alternative photo
     *
     * @return string|null
     */
    public function getAlternativePhoto()
    {
        return $this->translation[self::ALTERNATIVE_PHOTO];
    }

    /**
     * Set alternative photo
     *
     * @param $value
     * @return null|string
     */
    public function setAlternativePhoto($value)
    {
        return $this->translation[self::ALTERNATIVE_PHOTO] = $value;
    }

    /**
     * Get banner
     *
     * @return string|null
     */
    public function getBanner()
    {
        return $this->translation[self::BANNER];
    }

    /**
     * Set banner
     *
     * @param $value
     * @return null|string
     */
    public function setBanner($value)
    {
        return $this->translation[self::BANNER] = $value;
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->translation[self::DESCRIPTION];
    }

    /**
     * Set description
     *
     * @param $value
     * @return null|string
     */
    public function setDescription($value)
    {
        return $this->translation[self::DESCRIPTION] = $value;
    }

    /**
     * Get sort
     *
     * @return string|null
     */
    public function getSort()
    {
        return $this->getData(self::SORT);
    }

    /**
     * Set sort
     *
     * @param $value
     * @return null|string
     */
    public function setSort($value)
    {
        return $this->setData(self::SORT, $value);
    }

    /**
     * Set translation
     *
     * @param $value
     * @return array
     */
    public function setTranslation($value): array
    {
        return $this->translation = $value;
    }

    /**
     * Get translation
     *
     * @return array
     */
    public function getTranslation(): array
    {
        return $this->translation;
    }
}