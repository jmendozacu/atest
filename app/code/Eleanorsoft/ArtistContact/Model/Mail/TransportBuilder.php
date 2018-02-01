<?php

namespace Eleanorsoft\ArtistContact\Model\Mail;

use const bar\foo\baz\const1;
use Magento\Framework\Mail\Template\TransportBuilder as Transport;
/**
 * Class TransportBuilder
 * Override parent class
 *
 * @package Eleanorsoft\ArtistContact\Model\Mail
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class TransportBuilder extends Transport
{
    /**
     * add images
     *
     * @param $data
     * @return $this
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    public function addAttachment($data)
    {
       if ($data){
           for ($i = 0; $i < count($data); $i++){
               $this->message->createAttachment(
                   $data[$i]['content'],
                   'application/octet-stream',
                   \Zend_Mime::DISPOSITION_ATTACHMENT,
                   \Zend_Mime::ENCODING_BASE64,
                   $data[$i]['name']
               );
           }
       }
        return $this;
    }
}