<?php

namespace Eleanorsoft\ArtistContact\Block;

use Magento\Framework\View\Element\Template;


/**
 * Class ArtistContactForm
 * main Artist Contact form block
 *
 * @package Eleanorsoft\ArtistContact\Block
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class ArtistForm extends Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }

    public function getFormAction()
    {
        return $this->getUrl('eleanorsoft_artist/index/post', ['_secure' => true]);
    }
}