<?php

namespace Eleanorsoft\Contact\Plugin;

use \Magento\Framework\UrlInterface;
use \Magento\Contact\Block\ContactForm;

/**
 * Class ContactForm
 * todo: What is its purpose? What does it do?
 *
 * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
 * @copyright Copyright (c) 2017 Eleanorsoft (https://www.eleanorsoft.com/)
 */
class ContactFormPlugin
{
    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    public function __construct(
        UrlInterface $urlInterface
    ) {
        $this->urlInterface = $urlInterface;
    }

    public function afterGetFormAction(ContactForm $subject, $result)
    {
        return $this->urlInterface->getUrl('eleanorsoft_contact/index/post');
    }
}
