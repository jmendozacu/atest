<?php
/**
 * @category    CleverSoft
 * @package     CleverSlider
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverSlider\Block\Adminhtml\Formfields\NewAction\Fields\SliderItems;
use \Magento\Framework\App\ObjectManager;
class Types extends \Magento\Backend\Block\Template
{
    protected $_assetRepo;
    protected $_itemTypes;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = [])
    {
        $this->_assetRepo = $context->getAssetRepository();
        $this->_itemTypes = [];

        parent::__construct($context, $data);
    }
    protected function _getWidthClass(){
        $widthClass[] = ['label' => '-', 'value' => ''];
        for($i = 1; $i <= 24; $i++){
            $widthClass[] = ['label' => __('Width %1 %2: %3 px',$i,str_repeat('&nbsp;',ceil(4/(floor($i/10)+1))),round(($i/24)*1200)), 'value' => $i];
        }
        return $widthClass;
    }
    public function _construct(){
        parent::_construct();
        $types = [
            [
                'title' => __('Add Image'),
                'name' => 'image',
                'placeholder' => __('<i class="fa fa-file-image-o"></i>'),
                'content' => [
                    ['title' => __('Image'), 'name' => 'icon_img', 'type' => 'image', 'style' => 'display:block', 'button_text' => __('Select Image'), 'description' => 'Recommended size: at least 32px &times 32px'],
                    ['title' => __('Image Position'), 'name' => 'image_align', 'action' => 'clevermenu.changeOnPreview(this)', 'type' => 'dropdown',
                        'values' => [
                            ['label' => __('Center Center'), 'value' => 'center center'],
                            ['label' => __('Left Top'), 'value' => 'left top'],
                            ['label' => __('Left Center'), 'value' => 'left center'],
                            ['label' => __('Left Bottom'), 'value' => 'left bottom'],
                            ['label' => __('Right Top'), 'value' => 'right top'],
                            ['label' => __('Right Center'), 'value' => 'right center'],
                            ['label' => __('Right Bottom'), 'value' => 'right bottom'],
                            ['label' => __('Center Top'), 'value' => 'center top'],
                            ['label' => __('Center Bottom'), 'value' => 'center bottom']
                        ]
                    ],
                    ['title' => __('Heading'), 'name' => 'label', 'type' => 'text','value'=>'Slideshow'],
                    ['title' => __('Heading Text Color'), 'name' => 'bt_heading_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Heading Aligment'), 'name' => 'heading_align','action' => 'clevermenu.changeOnPreview(this)', 'type' => 'dropdown','selected_value' => 'center',
                        'values' => [
                            ['label' => __('Left'), 'value' => 'left'],
                            ['label' => __('Center'), 'value' => 'center'],
                            ['label' => __('Right'), 'value' => 'right']
                        ]
                    ],
                    ['title' => __('Sub Heading'), 'name' => 'sub_heading', 'type' => 'text','value'=>'Tell your brand\'s story through video and images'],
                    ['title' => __('Sub Heading Text Color'), 'name' => 'bt_sub_heading_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Sub Heading Aligment'), 'name' => 'sub_heading_align','action' => 'clevermenu.changeOnPreview(this)', 'type' => 'dropdown','selected_value' => 'center',
                        'values' => [
                            ['label' => __('Left'), 'value' => 'left'],
                            ['label' => __('Center'), 'value' => 'center'],
                            ['label' => __('Right'), 'value' => 'right']
                        ]
                    ],
                    ['title' => __('Button Label'), 'name' => 'bt_label', 'type' => 'text'],
                    ['title' => __('Button Label Color'), 'name' => 'bt_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Background Color'), 'name' => 'bt_bg_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Label Color Hover'), 'name' => 'bt_lb_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Background Color Hover'), 'name' => 'bt_bg_color_hover', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Aligment'), 'name' => 'bt_align', 'type' => 'dropdown','action' => 'clevermenu.changeOnPreview(this)',
                        'values' => [
                            ['label' => __('Left'), 'value' => 'left'],
                            ['label' => __('Center'), 'value' => 'center'],
                            ['label' => __('Right'), 'value' => 'right']
                        ]
                    ],
                    ['title' => __('Show Overlay'), 'name' => 'show_overlay', 'type' => 'dropdown','action' => 'clevermenu.changeOnPreview(this)',
                        'values' => [
                            ['label' => __('No'), 'value' => ''],
                            ['label' => __('Yes'), 'value' => 'show_overlay']
                        ]
                    ],
                    ['title' => __('Slide Link'), 'name' => 'slide_link', 'type' => 'text'],
                ]
            ],
            [
                'title' => __('Add Video'),
                'name' => 'video',
                'placeholder' => __('<i class="fa fa-youtube-play"></i>'),
                'content' => [
                    ['title' => __('Video Type'), 'name' => 'video_type', 'type' => 'dropdown','values' => [
                        ['label' => __('Youtube'), 'value' => 0],
                        ['label' => __('Vimeo'), 'value' => 1]
                    ]],
                    ['title' => __('Youtube/ Vimeo Video ID'), 'name' => 'youtube_id', 'type' => 'text', 'value' => '_9VUPq3SxOc'],
                    ['title' => __('Style'), 'name' => 'style_video', 'type' => 'dropdown','action'=>'clevermenu.changeOnPreview(this)',
                        'values' => [
                            ['label' => __('Image with play button'), 'value' => 0],
                            ['label' => __('Background video with play button'), 'value' => 1],
                            ['label' => __('Background video'), 'value' => 2]
                        ]
                    ],
                    ['title' => __('Image Holder'), 'name' => 'icon_img', 'type' => 'image', 'style' => 'display:block', 'button_text' => __('Select Image'), 'description' => 'Recommended size: at least 32px &times 32px'],
                    ['title' => __('Heading'), 'name' => 'label', 'type' => 'text'],
                    ['title' => __('Heading Aligment'), 'name' => 'heading_align', 'type' => 'dropdown','action' => 'clevermenu.changeOnPreview(this)','selected_value' => 'center',
                        'values' => [
                            ['label' => __('Left'), 'value' => 'left'],
                            ['label' => __('Center'), 'value' => 'center'],
                            ['label' => __('Right'), 'value' => 'right']
                        ]
                    ],
                    ['title' => __('Sub Heading'), 'name' => 'sub_heading', 'type' => 'text','value'=>'Slideshow'],
                    ['title' => __('Sub Heading Aligment'), 'name' => 'sub_heading_align', 'type' => 'dropdown','action' => 'clevermenu.changeOnPreview(this)','selected_value' => 'center',
                        'values' => [
                            ['label' => __('Left'), 'value' => 'left'],
                            ['label' => __('Center'), 'value' => 'center'],
                            ['label' => __('Right'), 'value' => 'right']
                        ]
                    ],
                    ['title' => __('Button Label'), 'name' => 'bt_label', 'type' => 'text'],
                    ['title' => __('Button Label Color'), 'name' => 'bt_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Background Color'), 'name' => 'bt_bg_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Label Color Hover'), 'name' => 'bt_lb_color', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Background Color Hover'), 'name' => 'bt_bg_color_hover', 'custom' => 'colorpicker', 'type' => 'text','action' => 'clevermenu.changeOnPreview(this)'],
                    ['title' => __('Button Aligment'), 'name' => 'bt_align', 'type' => 'dropdown','action' => 'clevermenu.changeOnPreview(this)',
                        'values' => [
                            ['label' => __('Left'), 'value' => 'left'],
                            ['label' => __('Center'), 'value' => 'center'],
                            ['label' => __('Right'), 'value' => 'right']
                        ]
                    ],
                    ['title' => __('Slide Link'), 'name' => 'slide_link', 'type' => 'text'],
                ]
            ],
        ];
        $this->setItemTypes($types);
    }
    public function getMediaUrl($url = ''){
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$url;
    }
    public function addNewType($type){
        array_push($this->_itemTypes,$type);
        return $this;
    }

    public function setItemTypes($types){
        $this->_itemTypes = $types;
        return $this;
    }
    public function getItemTypes(){
        return $this->_itemTypes;
    }
    public function getItemTypesJson(){
        return json_encode($this->_itemTypes);
    }

    public function getColumnTemplates(){
        return
            [
                [
                    'title' => __('Aplly for All'),
                    'type' => 'heading'
                ],
                [
                    'title' => __(''),
                    'type' => 'layout01',
                    'image' => $this->getImageUrl('layout_extension/itp.jpg'),
                    'col' => 1
                ],
                [
                    'title' => __(''),
                    'type' => 'layout03',
                    'image' => $this->getImageUrl('layout_extension/tp.jpg'),
                    'col' => 1
                ],
                [
                    'title' => __(''),
                    'type' => 'layout05',
                    'image' => $this->getImageUrl('layout_extension/tpi.jpg'),
                    'col' => 1
                ],
                [
                    'title' => __(''),
                    'type' => 'layout02',
                    'image' => $this->getImageUrl('layout_extension/itl.jpg'),
                    'col' => 1
                ],

                [
                    'title' => __(''),
                    'type' => 'layout04',
                    'image' => $this->getImageUrl('layout_extension/tl.jpg'),
                    'col' => 1
                ],

                [
                    'title' => __(''),
                    'type' => 'layout06',
                    'image' => $this->getImageUrl('layout_extension/tli.jpg'),
                    'col' => 1
                ]
            ];
    }
    public function getImageUrl($path){
        return $this->_assetRepo->getUrl('CleverSoft_CleverSlider/images/'.$path);
    }
}
?>