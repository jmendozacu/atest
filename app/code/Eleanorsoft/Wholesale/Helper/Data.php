<?php

namespace Eleanorsoft\Wholesale\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Wholesale base helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_ENABLED = 'eleanorsoft_wholesale/general/enabled';
    const XML_PATH_EMAIL_TEMPLATE = 'eleanorsoft_wholesale/email/email_template';
    const XML_PATH_EMAIL_RECIPIENT = 'eleanorsoft_wholesale/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'eleanorsoft_wholesale/email/sender_email_identity';
}
