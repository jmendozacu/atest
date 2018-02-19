<?php

namespace Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid;

use Eleanorsoft\DesignersPage\Controller\Adminhtml\Designer;
/**
 * Class Index
 *
 * @package Eleanorsoft\DesignersPage\Controller\Adminhtml\Grid
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Index extends Designer
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function execute()
    {
        $result = $this->resultPageFactory->create();
        return $this->initPage($result);
    }
}