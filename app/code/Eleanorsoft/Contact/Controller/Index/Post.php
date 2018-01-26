<?php

namespace Eleanorsoft\Contact\Controller\Index;

use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\App\Response\Http;
use \Magento\Contact\Controller\Index\Post as BaseIndex;
use \Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Psr\Log\LoggerInterface;
use Magento\Framework\DataObject;

class Post extends BaseIndex
{
    const HANDLE_ERROR_STATUS_CODE = 20180117;
    private $mail;

    public function __construct(
        Context $context,
        ConfigInterface $contactsConfig,
        MailInterface $mail,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger = null
    ) {
        $this->mail = $mail;
        parent::__construct($context, $contactsConfig, $mail, $dataPersistor, $logger);
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
        if (!$this->isPostRequest()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        $post = $this->getRequest()->getPostValue();

        $this->getRequest()->isAjax() ? $this->_executeAjax($post) : parent::execute();
    }

    /**
     * @return bool
     */
    private function isPostRequest()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        return !empty($request->getPostValue());
    }

    private function _executeAjax($post)
    {
//        $this->inlineTranslation->suspend();

        try {
            $this->sendMail($post);
//            $this->inlineTranslation->resume();

            $code = Http::STATUS_CODE_200;
            $message = __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
        } catch (\Exception $e) {
//            $this->inlineTranslation->resume();

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

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($post);

        $error = $this->getPostFieldValuesError($post);

        if ($error) {
            throw new \Exception($error, self::HANDLE_ERROR_STATUS_CODE);
        }

        $this->mail->send(
            $post['email'],
            ['data' => $postObject]
        );

//        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
//        $transport = $this->_transportBuilder
//            ->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
//            ->setTemplateOptions(
//                [
//                    'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
//                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
//                ]
//            )
//            ->setTemplateVars(['data' => $postObject])
//            ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
//            ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
//            ->setReplyTo($post['email'])
//            ->getTransport();
//
//        $transport->sendMessage();
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

        if (!$this->validateFieldValue($post['subject'], 'NotEmpty')) {
            $errorMessage = 'Subject field is empty.';
        }

        if (!$this->validateFieldValue($post['email'], 'EmailAddress')) {
            $errorMessage = 'Email is not correct.';
        }

        return $errorMessage;
    }

    /**
     * Get Data Persistor
     *
     * @return DataPersistorInterface
     */
    private function getDataPersistor()
    {
        if ($this->dataPersistor === null) {
            $this->dataPersistor = ObjectManager::getInstance()
                ->get(DataPersistorInterface::class);
        }

        return $this->dataPersistor;
    }
}
