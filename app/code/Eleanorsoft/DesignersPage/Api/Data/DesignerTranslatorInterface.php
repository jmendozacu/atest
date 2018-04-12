<?php

namespace Eleanorsoft\DesignersPage\Api\Data;


/**
 * Class DesignerTranslatorInterface
 *
 * @package Eleanorsoft_DesignersPage
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

interface DesignerTranslatorInterface
{

    /**
     * Set translation
     *
     * @param $value
     * @return array
     */
    public function setTranslation($value):array ;

    /**
     * Get translation
     *
     * @return array
     */
    public function getTranslation():array ;
}