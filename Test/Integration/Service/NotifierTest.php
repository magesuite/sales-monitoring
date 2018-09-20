<?php

namespace MageSuite\SalesMonitoring\Test\Integration\Service;

class NotifierTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\SalesMonitoring\Model\CheckFactory
     */
    private $checkFactory;

    protected function setUp()
    {
        $this->checkFactory = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \MageSuite\SalesMonitoring\Model\CheckFactory::class
        );
    }

    protected function createNotifierMock($slackWebhook, $mailer)
    {
        $scopeConfig = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);

        $scopeConfig
            ->method('getValue')
            ->will($this->returnValueMap([
              ['sales_monitoring/notifications/project_name', 'default', null, 'creativeshop'],
              ['sales_monitoring/notifications/emails', 'default', null, '["contact@creativeshop.io"]'],
              ['sales_monitoring/notifications/slack_hooks', 'default', null, '{"a": {"url": "https://slack.com/fake-hook", "channel": "#m2c-notifications"}, "b": {"url": "https://slack.com/fake-hook2", "channel": "#general"}}']
            ]))
        ;

        $config = new \MageSuite\SalesMonitoring\Model\Config(
            $scopeConfig,
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
                \Magento\Framework\Serialize\Serializer\Json::class
            )
        );

        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

        return new \MageSuite\SalesMonitoring\Service\Notifier(
            $config,
            $slackWebhook,
            $mailer,
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
                \Magento\Store\Model\StoreManagerInterfaceFactory::class
            ),
            $logger
        );
    }

    public function testThatAlarmEmailsAreSent()
    {
        $check = $this->checkFactory->create();
        $check->triggerAlarm();
        $check->setName('Aloha');
        $check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria());

        $slackWebhook = $this->createMock(\MageSuite\SalesMonitoring\Service\SlackWebhook::class);

        $mailer = $this->createMock(\MageSuite\SalesMonitoring\Service\Mailer::class);

        $mailer
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo(\MageSuite\SalesMonitoring\Service\Notifier::EMAIL_TEMPLATE),
                $this->equalTo(['contact@creativeshop.io']),
                $this->contains('Aloha')
            )
        ;

        $notifier = $this->createNotifierMock($slackWebhook, $mailer);

        $notifier->execute(new \Magento\Framework\Event\Observer([
            'check' => $check
        ]));
    }

    public function testThatOkEmailsAreSent()
    {
        $check = $this->checkFactory->create();
        $check->markOK();
        $check->setName('Aloha');
        $check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria());

        $slackWebhook = $this->createMock(\MageSuite\SalesMonitoring\Service\SlackWebhook::class);

        $mailer = $this->createMock(\MageSuite\SalesMonitoring\Service\Mailer::class);

        $mailer
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo(\MageSuite\SalesMonitoring\Service\Notifier::EMAIL_TEMPLATE),
                $this->equalTo(['contact@creativeshop.io']),
                $this->contains('Aloha')
            )
        ;

        $notifier = $this->createNotifierMock($slackWebhook, $mailer);

        $notifier->execute(new \Magento\Framework\Event\Observer([
            'check' => $check
        ]));
    }

    public function testThatSlackAlarmMessagesArePosted()
    {
        $check = $this->checkFactory->create();
        $check->triggerAlarm();
        $check->setName('Aloha');
        $check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria());

        $slackWebhook = $this->createMock(\MageSuite\SalesMonitoring\Service\SlackWebhook::class);

        $slackWebhook
            ->expects($this->exactly(2))
            ->method('post')
            ->with(
                $this->anything(),
                $this->logicalAnd(
                    $this->stringContains('Aloha', true),
                    $this->stringContains('ALARM', true),
                    $this->stringContains('creativeshop', true)
                ),
                $this->anything(),
                $this->anything()
            )
        ;

        $mailer = $this->createMock(\MageSuite\SalesMonitoring\Service\Mailer::class);

        $notifier = $this->createNotifierMock($slackWebhook, $mailer);

        $notifier->execute(new \Magento\Framework\Event\Observer([
            'check' => $check
        ]));
    }

    public function testThatSlackOkMessagesArePosted()
    {
        $check = $this->checkFactory->create();
        $check->markOK();
        $check->setName('Aloha');
        $check->setCriteria(new \MageSuite\SalesMonitoring\Model\AlarmCriteria());

        $slackWebhook = $this->createMock(\MageSuite\SalesMonitoring\Service\SlackWebhook::class);

        $slackWebhook
            ->expects($this->exactly(2))
            ->method('post')
            ->with(
                $this->anything(),
                $this->logicalAnd(
                    $this->stringContains('Aloha', true),
                    $this->stringContains('OK', true),
                    $this->stringContains('creativeshop', true),
                    $this->logicalNot(
                        $this->stringContains('ALARM', true)
                    )
                ),
                $this->anything(),
                $this->anything()
            )
        ;

        $mailer = $this->createMock(\MageSuite\SalesMonitoring\Service\Mailer::class);

        $notifier = $this->createNotifierMock($slackWebhook, $mailer);

        $notifier->execute(new \Magento\Framework\Event\Observer([
            'check' => $check
        ]));
    }
}