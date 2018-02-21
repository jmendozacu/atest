<?php
namespace Eleanorsoft\DesignersPage\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class IndexBase
 *
 * @package Eleanorsoft\DesignersPage\Controller
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

abstract class IndexBase extends Action
{


    /**
     * @var PageFactory
     */
    protected $pageFactory;

    public function __construct
    (
        Context $context,
        PageFactory $pageFactory
    )
    {
        parent::__construct($context);

        $this->pageFactory = $pageFactory;
    }
}