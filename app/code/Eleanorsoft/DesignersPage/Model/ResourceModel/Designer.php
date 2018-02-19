<?php

namespace Eleanorsoft\DesignersPage\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


/**
 * Class Designer
 * todo: What is its purpose? What does it do?
 *
 * @package Eleanorsoft\DesignersPage\Model\ResourceModel
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Designer extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('eleanorsoft_designers', 'designer_id');
    }
}