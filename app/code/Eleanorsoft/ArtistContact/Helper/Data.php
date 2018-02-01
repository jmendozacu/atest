<?php

namespace Eleanorsoft\ArtistContact\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 * Artist Contact helper
 *
 * @package Eleanorsoft\ArtistContact\Helper
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Data extends AbstractHelper
{
    const XML_PATH_ENABLED = 'eleanorsoft_artist_contact/general/enabled';
    const XML_PATH_EMAIL_TEMPLATE = 'eleanorsoft_artist_contact/email/email_template';
    const XML_PATH_EMAIL_RECIPIENT = 'eleanorsoft_artist_contact/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'eleanorsoft_artist_contact/email/sender_email_identity';
}