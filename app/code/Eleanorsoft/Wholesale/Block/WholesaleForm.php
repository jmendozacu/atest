<?php

namespace Eleanorsoft\Wholesale\Block;

use Magento\Framework\View\Element\Template;

/**
 * Main Wholesale form block
 */
class WholesaleForm extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * Returns action url for wholesale form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('eleanorsoft_wholesale/index/post', ['_secure' => true]);
    }
}
