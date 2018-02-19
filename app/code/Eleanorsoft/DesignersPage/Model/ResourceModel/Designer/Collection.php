<?php

namespace Eleanorsoft\DesignersPage\Model\ResourceModel\Designer;

use Eleanorsoft\DesignersPage\Model\Designer;
use Eleanorsoft\DesignersPage\Model\ResourceModel\Designer as ResourceDesigner;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


/**
 * Class Collection
 *
 * @package Eleanorsoft\DesignersPage\Model\ResourceModel\Designer
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'designer_id';

    protected function _construct()
    {
        $this->_init(Designer::class, ResourceDesigner::class);
    }
}