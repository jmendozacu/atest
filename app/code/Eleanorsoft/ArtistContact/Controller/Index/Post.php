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
        } finally {
            $this->inlineTranslation->resume();
        }

        $this->getResponse()->setStatusCode($code)->setContent($message);
    }

    /**
     * get file data
     *
     * @return array
     * @throws \Exception
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function getDataImg()
    {
        $dataImg = array();
        if (isset($_FILES['photo']['name']) and is_array($_FILES['photo']['name'])) {
            for ($i = 0; $i < count($_FILES['photo']['name']); $i++){
                if ($this->uploadedFileExists($i)) {
                    if ($this->validateUploadedFile($i)) {
                        $img_name = basename($_FILES['photo']['name'][$i]);
                        $imgContent = file_get_contents($_FILES['photo']['tmp_name'][$i]);
                        $dataImg[$i]['name'] = $img_name;
                        $dataImg[$i]['content'] = $imgContent;
                    } else {
                        throw new \Exception("Invalid file Type or file Size Exceeded",
                            self::HANDLE_ERROR_STATUS_CODE);
                    }
                }

            }
        }

        return $dataImg;
    }

    private function uploadedFileExists($i)
    {
        return (intval($_FILES['photo']['size'][$i]) > 0);
    }

    /**
     * validate uploaded file
     *
     * @param $i
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateUploadedFile($i)
    {
        return (
            $this->validateMimeType($i)
            && $this->validateSize($i)
            && $this->validateExtension($i)
        );
    }

    /**
     * valid file type
     *
     * @param $i
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateMimeType($i)
    {
        $tmp = explode('/', $_FILES["photo"]["type"][$i]);
        return ($tmp and $tmp[0] == 'image');
    }

    /**
     * valid file size
     *
     * @param $i
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateSize($i)
    {
        return ($_FILES["photo"]["size"][$i] <= 3 * 1024 * 1024);
    }

    /**
     * valid file extension
     *
     * @param $i
     * @return bool
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function validateExtension($i)
    {
        $validExtensions = array("jpeg", "jpg", "png");
        return in_array(pathinfo($_FILES['photo']['name'][$i], PATHINFO_EXTENSION), $validExtensions);
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
     */
    private function getPostFieldValuesError($post)
    {
        if (!$this->validateFieldValue($post['name'], 'NotEmpty')) {
            return 'Name field is empty.';
        }

        if (!$this->validateFieldValue($post['email'], 'EmailAddress')) {
            return 'Email is not correct.';
        }

        if (!$this->validateFieldValue($post['phone'], 'NotEmpty')) {
            return 'Phone Number field is empty.';
        }

        if (!$this->validateFieldValue($post['yourself'], 'NotEmpty')) {
            return '\'A little bit about yourself\' field is empty.';
        }
    }

}