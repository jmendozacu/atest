<?php
/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\Base\Controller\Adminhtml\System\Config;

abstract class Demo extends \Magento\Backend\App\Action {
    protected function _import()
    {
        return $this->_objectManager->get('CleverSoft\Base\Model\Import\Demo')
            ->importDemo($this->getRequest()->getParam('demo_version'),$this->getRequest()->getParam('store_view'));
    }
}
