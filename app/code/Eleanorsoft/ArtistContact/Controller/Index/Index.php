<?php

namespace Eleanorsoft\ArtistContact\Controller\Index;

use Eleanorsoft\ArtistContact\Controller\Index as BaseIndex;
use Magento\Framework\App\ResponseInterface;

/**
 * Class Index
 * todo: What is its purpose? What does it do?
 *
 * @package Eleanorsoft\ArtistContact\Controller\Index
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Index extends BaseIndex
{

    /**
     * Show Artist Contact page
     *
     * Execute action based on request and return result
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}