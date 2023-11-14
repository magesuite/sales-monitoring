<?php

namespace MageSuite\SalesMonitoring\Service;

class Mailer
{
    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    private $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilderFactory
     */
    private $transportBuilderFactory;

    public function __construct(
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilderFactory $transportBuilderFactory
    ) {
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilderFactory = $transportBuilderFactory;
    }

    public function send(string $templateId, array $addressees, array $vars)
    {
        if (empty($addressees)) {
            return;
        }

        $builder = $this->transportBuilderFactory->create();
        $builder->setTemplateIdentifier($templateId);
        $builder->addTo($addressees[0]);
        $builder->addBcc(array_slice($addressees, 1));
        $builder->setTemplateVars($vars);
        $builder->setTemplateOptions([
            'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
        ]);

        $this->inlineTranslation->suspend();
        $builder->getTransport()->sendMessage();
        $this->inlineTranslation->resume();
    }
}
