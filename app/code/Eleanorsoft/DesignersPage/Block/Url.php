<?php

namespace Eleanorsoft\DesignersPage\Block;
use Magento\Backend\Block\Template;


/**
 * Class Url
 * todo: What is its purpose? What does it do?
 *
 * @package Eleanorsoft_
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Url extends Template
{
    public function currentStore()
    {
        $store = $this->getRequest()->getParam('store');

        return $store;
    }
}