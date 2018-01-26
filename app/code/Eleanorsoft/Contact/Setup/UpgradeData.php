<?php

namespace Eleanorsoft\Contact\Setup;

use Magento\Framework\App\State;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Email\Model\Template\Config as EmailTemplateConfig;
use Magento\Email\Model\BackendTemplate as EmailBackendTemplate;
use Magento\Email\Model\BackendTemplateFactory as EmailBackendTemplateFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var State
     */
    protected $state;

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var EmailTemplateConfig
     */
    protected $emailTemplateConfig;

    /**
     * @var EmailBackendTemplateFactory
     */
    protected $emailBackendTemplateFactory;

    public function __construct(
        State $state,
        WriterInterface $configWriter,
        EmailTemplateConfig $emailTemplateConfig,
        EmailBackendTemplateFactory $emailBackendTemplateFactory
    ) {
        $this->state = $state;
        $this->configWriter = $configWriter;
        $this->emailTemplateConfig = $emailTemplateConfig;
        $this->emailBackendTemplateFactory = $emailBackendTemplateFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->createEmailContactFormTemplate();
        }

        $setup->endSetup();
    }

    /**
     * @return EmailBackendTemplate
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function getEmailBackendTemplate()
    {
        return $this->emailBackendTemplateFactory->create();
    }

    /**
     * @author Eugene Polischuk <eugene.polischuk@eleanorsoft.com>
     */
    public function createEmailContactFormTemplate()
    {
        $this->state->setAreaCode('adminhtml');

        $templateId = 'Eleanorsoft Contact Form';
        $originTemplateId = 'eleanorsoft_contact_email_email_template';
        $originTemplate = $this->getEmailBackendTemplate()->loadDefault($originTemplateId);

        $savedTemplateId = $this->getEmailBackendTemplate()
            ->setTemplateCode($templateId)
            ->setOrigTemplateCode($originTemplateId)
            ->setTemplateText($originTemplate->getTemplateText())
            ->setTemplateSubject($originTemplate->getTemplateSubject())
            ->setTemplateType($originTemplate->getTemplateType())
            ->setOrigTemplateVariables($originTemplate->getOrigTemplateVariables())
            ->save()
            ->getId();

        $this->configWriter->save('contact/email/email_template', $savedTemplateId);
    }
}
