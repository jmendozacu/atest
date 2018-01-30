<?php

namespace Eleanorsoft\Wholesale\Controller\Index;

use Eleanorsoft\Wholesale\Controller\Index as BaseIndex;

class Index extends BaseIndex
{
    /**
     * Show Contact Us page
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
