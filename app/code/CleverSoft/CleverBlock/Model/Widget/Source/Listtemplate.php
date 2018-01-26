<?php
/**
 * @category    CleverSoft
 * @package     CleverBlock
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

class CleverProduct_Model_Widget_Source_Listtemplate
{
    public function toOptionArray()
    {

        $path = Mage::getBaseDir('design').'\frontend\cleversoftbase\default\template\mt\widget';
        $list_files = array_diff(scandir($path), array('..', '.'));

        $result[] = array(
            'value' => 'mt/widget/default.phtml',
            'label' => 'default.phtml',
        );
        foreach ($list_files as $file){
            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) continue;
            $result[] = array(
                            'value' => 'mt/widget/'.$file,
                            'label' => $file,
                        );
        }

        return $result;
    }
}