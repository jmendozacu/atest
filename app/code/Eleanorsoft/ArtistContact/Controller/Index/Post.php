<?php

namespace Eleanorsoft\ArtistContact\Controller\Index;

use Eleanorsoft\ArtistContact\Controller\Index as BaseIndex;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NotFoundException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Eleanorsoft\ArtistContact\Helper\Data as BaseHelper;

/**
 * Class Post
 *
 * @package Eleanorsoft\ArtistContact\Controller\Index
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Post extends BaseIndex
{
    const HANDLE_ERROR_STATUS_CODE = 20180117;

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        if (!$post) {
            $this->_redirect('*/*/');
        }
        if ($this->getRequest()->isAjax()) {
            $this->_executeAjax($post);
        } else {
            throw new NotFoundException(__('Page not found.'));
        }
    }

    /**
     * Processes the ajax request
     *
     * @param $post
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function _executeAjax($post)
    {
        try {
            $error = $this->getPostFieldValuesError($post);

            if ($error) {
                throw new \Exception($error, self::HANDLE_ERROR_STATUS_CODE);
            }

            $postObject = new DataObject();
            $postObject->setData($post);
            $dataImg = $this->getDataImg();

            $this->sendMail($post['email'], $postObject, $dataImg);
            $code = Http::STATUS_CODE_200;
            $message = __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
        } catch (\Exception $e) {

            if ($e->getCode() == self::HANDLE_ERROR_STATUS_CODE) {
                $code = Http::STATUS_CODE_406;
                $message = __($e->getMessage());
            } else {
                $code = Http::STATUS_CODE_500;
                $message = __($e->getMessage());
            }
        }

        $this->getResponse()->setStatusCode($code)->setContent($message);
    }


    public function getDataImg()
    {
        $dataImg = array();
        $files = $this->getRequest()->getFiles()->get('photo');

        if (count($files) > 0){
            for($i = 0; $i < count($files); $i++){ $file = $files[$i];
                if ($this->uploadedFileExists($file['size'])){
                    if ($this->validateUploadedFile($file)){
                        $imgName = basename($file['name']);
                        $imgContent = file_get_contents($file['tmp_name']);
                        $dataImg[$i]['name'] = $imgName;
                        $dataImg[$i]['content'] = $imgContent;
                    }
                }else {
                    throw new \Exception("Invalid file Type or file Size Exceeded",
                        self::HANDLE_ERROR_STATUS_CODE);
                }
            }
        }
        return $dataImg;
    }

    private function uploadedFileExists($size)
    {
        return (intval($size) > 0);
    }

    /**
     * validate uploaded file
     *
     * @param $i
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateUploadedFile($file)
    {
        return (
            $this->validateMimeType($file['type'])
            && $this->validateSize($file['size'])
            && $this->validateExtension($file['name'])
        );
    }

    /**
     * valid file type
     *
     * @param $type
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateMimeType($type)
    {
        $tmp = explode('/', $type);
        return ($tmp && $tmp[0] == 'image');
    }

    /**
     * valid file size
     *
     * @param $size
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateSize($size)
    {
        return ($size <= 3 * 1024 * 1024);
    }

    /**
     * valid file extension
     *
     * @param $extension
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateExtension($extension)
    {
        $validExtensions = array("jpeg", "jpg", "png", "JPEG", "JPG", "PNG");
        return in_array(pathinfo($extension, PATHINFO_EXTENSION), $validExtensions);
    }

    /**
     * Sending mail
     *
     * @param $post
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function sendMail($replyTo, $variables, $dataImg)
    {
        $this->inlineTranslation->suspend();

            $storeScope = ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->scopeConfig->getValue(BaseHelper::XML_PATH_EMAIL_TEMPLATE, $storeScope))
                ->setTemplateOptions(
                    [
                        'area' => FrontNameResolver::AREA_CODE,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $variables])
                ->setFrom($this->scopeConfig->getValue(BaseHelper::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($this->scopeConfig->getValue(BaseHelper::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                ->setReplyTo($replyTo)
                ->addAttachment($dataImg)
                ->getTransport();
                $transport->sendMessage();
    }

    /**
     * Validating form data
     *
     * @param $post
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateFieldValue($value, $rule)
    {
        if(!\Zend_Validate::is(trim($value), $rule)) {
            return false;
        }
        return true;
    }

    /**
     * Returns an error message
     *
     * @param $post
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     * @return string
     */
    private function getPostFieldValuesError($post)
    {
        $error = '';
        if (!$this->validateFieldValue($post['name'], 'NotEmpty')) {
            $error = 'Name field is empty.';
        }

        if (!$this->validateFieldValue($post['email'], 'EmailAddress')) {
            $error = 'Email is not correct.';
        }

        if (!$this->validateFieldValue($post['phone'], 'NotEmpty')) {
            $error = 'Phone Number field is empty.';
        }

        if (!$this->validateFieldValue($post['yourself'], 'NotEmpty')) {
            $error = '\'A little bit about yourself\' field is empty.';
        }
        return $error;
    }

}