<?php

namespace Eleanorsoft\DesignersPage\Model;

use Eleanorsoft\DesignersPage\Api\Data\DesignerInterface;
use Eleanorsoft\DesignersPage\Api\Data\DesignerInterfaceFactory;
use Eleanorsoft\DesignersPage\Api\DesignerRepositoryInterface;
use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer\CollectionFactory;


/**
 * Class DesignerRepository
 *
 * @package Eleanorsoft\DesignersPage\Model
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class DesignerRepository implements DesignerRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var Designer
     */
    protected $resourceModel;

    /**
     * @var DesignerFactory
     */
    protected $designerFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * DesignerRepository constructor.
     * @param DesignerInterfaceFactory $designerFactory
     * @param Designer $resourceModel
     * @param CollectionFactory $collectionFactory
     * @internal param DesignerFactory $blockFactory
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     */
    public function __construct
    (
        DesignerInterfaceFactory $designerFactory,
        Designer $resourceModel,
        CollectionFactory $collectionFactory
    )
    {
        $this->resourceModel = $resourceModel;
        $this->designerFactory = $designerFactory;
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * Save designer.
     *
     * @param DesignerInterface $designer
     * @return \Magento\Cms\Api\Data\BlockInterface
     * @throws CouldNotSaveException
     */
    public function save(DesignerInterface $designer)
    {
        try{
            $designer->setDataChanges(true);
            $this->resourceModel->save($designer);

        }catch (\Exception $exception){

            throw new CouldNotSaveException(
                __('Could not save the designer: %1', $exception->getMessage())
            );
        }
    }

    /**
     * Retrieve designer.
     *
     * @param int $id
     * @return \Magento\Cms\Api\Data\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id)
    {
        if (!isset($this->instances[$id])) {

            $designer = $this->designerFactory->create();
            $this->resourceModel->load($designer, $id);

            if (!$designer->getId()) {
                throw new NoSuchEntityException(__('Requested image doesn\'t exist'));
            }
            $this->instances[$id] = $designer;
        }
        return $this->instances[$id];
    }

    /**
     * Retrieve designers matching the specified criteria.
     *
     * @param $value
     * @return \Magento\Cms\Api\Data\BlockSearchResultsInterface
     * @internal param SearchCriteriaInterface $searchCriteria
     */
    public function getList($value)
    {
        $collection = $this->collectionFactory->create();
        return  $collection->setOrder('sort', $value);
    }

    /**
     * Delete designer.
     *
     * @param DesignerInterface $designer
     * @return bool true on success
     * @throws CouldNotSaveException
     * @throws StateException
     * @internal param \Magento\Cms\Api\Data\BlockInterface $block
     */
    public function delete(DesignerInterface $designer)
    {
        /** @var TYPE_NAME $designer */
        $id = $designer->getId();
        try {
            unset($this->instances[$id]);
            $this->resourceModel->delete($designer);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove image %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete designer by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }

}