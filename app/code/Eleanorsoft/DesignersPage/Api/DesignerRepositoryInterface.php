<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eleanorsoft\DesignersPage\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * CMS block CRUD interface.
 * @api
 * @since 100.0.2
 */
interface DesignerRepositoryInterface
{
    /**
     * Save designer.
     *
     * @param Data\DesignerInterface $designer
     * @return \Magento\Cms\Api\Data\BlockInterface
     */
    public function save(Data\DesignerInterface $designer);

    /**
     * Retrieve designer.
     *
     * @param int $id
     * @return \Magento\Cms\Api\Data\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve designers matching the specified criteria.
     *
     * @param $value
     * @return \Magento\Cms\Api\Data\BlockSearchResultsInterface
     * @internal param SearchCriteriaInterface $searchCriteria
     */
    public function getList($value);

    /**
     * Delete designer.
     *
     * @param Data\DesignerInterface $designer
     * @return bool true on success
     * @internal param \Magento\Cms\Api\Data\BlockInterface $block
     */
    public function delete(Data\DesignerInterface $designer);

    /**
     * Delete designer by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);

}
