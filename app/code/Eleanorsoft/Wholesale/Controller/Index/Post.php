<?php

namespace Eleanorsoft\Wholesale\Controller\Index;

use Magento\Framework\App\Response\Http;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NotFoundException;
use Eleanorsoft\Wholesale\Controller\Index as BaseIndex;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Eleanorsoft\Wholesale\Helper\Data as BaseHelper;


class Post extends BaseIndex
{
    const HANDLE_ERROR_STATUS_CODE = 20180117;

    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        if (!$post) {
            $this->_redirect('*/*/');

            return;
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

            $this->sendMail($post['email'], $postObject);

            $code = Http::STATUS_CODE_200;
            $message = __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
        } catch (\Exception $e) {

            if ($e->getCode() == self::HANDLE_ERROR_STATUS_CODE) {
                $code = Http::STATUS_CODE_406;
                $message = __($e->getMessage());
            } else {
                $code = Http::STATUS_CODE_500;
                $message = __('We can\'t process your request right now. Sorry, that\'s all we know.');
            }
        } finally {
            $this->inlineTranslation->resume();
        }

        $this->getResponse()->setStatusCode($code)->setContent($message);

        return;
    }

    /**
     * Sending mail
     *
     * @param $post
     * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
     * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
     */
    private function sendMail($replyTo, $variables)
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
            ->getTransport();
        $transport->sendMessage();
    }

    /**
     * Validating form data
     *
     * @param $value
     * @param $rule
     * @return bool
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

    private function getPostFieldValuesError($post)
    {
        $errorMessage = '';

        if (!$this->validateFieldValue($post['name'], 'NotEmpty')) {
            $errorMessage = 'Name field is empty.';
        }

        if (!$this->validateFieldValue($post['email'], 'EmailAddress')) {
            $errorMessage = 'Email is not correct.';
        }

        if (!$this->validateFieldValue($post['phone'], 'NotEmpty')) {
            $errorMessage = 'Company field is empty.';
        }

        if (!$this->validateFieldValue($post['comment'], 'NotEmpty')) {
            $errorMessage = 'Message field is empty.';
        }

        return $errorMessage;
    }
}
