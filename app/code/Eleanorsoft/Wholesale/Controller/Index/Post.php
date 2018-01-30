<?php

namespace Eleanorsoft\Wholesale\Controller\Index;

use Braintree\Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NotFoundException;
use Eleanorsoft\Wholesale\Helper\Data as BaseHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Eleanorsoft\Wholesale\Controller\Index as BaseIndex;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;

class Post extends BaseIndex
{
    const HANDLE_ERROR_STATUS_CODE = 20180117;
    private $mail;

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        MailInterface $mail
    ) {
        $this->mail = $mail;
        parent::__construct(
            $context,
            $transportBuilder,
            $inlineTranslation,
            $scopeConfig,
            $storeManager);
    }

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

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

    private function _executeAjax($post)
    {
        try {
            $this->sendMail($post);

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
        }

        $this->getResponse()->setStatusCode($code)->setContent($message);

        return;
    }

    private function sendMail($post)
    {
        $postObject = new DataObject();
        $postObject->setData($post);

        $error = $this->getPostFieldValuesError($post);

        if ($error) {
            throw new \Exception($error, self::HANDLE_ERROR_STATUS_CODE);
        }

        $this->mail->send(
            $post['email'],
            ['data' => $postObject]
        );
    }

    private function validateFieldValue($value, $rule)
    {
        try {
            if(!\Zend_Validate::is(trim($value), $rule)) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
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
