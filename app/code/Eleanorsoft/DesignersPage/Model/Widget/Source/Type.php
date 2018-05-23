<?php

namespace Eleanorsoft\DesignersPage\Model\Widget\Source;
use Magento\Framework\Option\ArrayInterface;


/**
 * Class Type
 * todo: What is its purpose? What does it do?
 *
 * @package Eleanorsoft_
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Type implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $types = [
            ['value' => 'carousel', 'label' => __('Carousel')],
        ];
        return $types;
    }
}