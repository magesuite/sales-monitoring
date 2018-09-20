<?php

namespace MageSuite\SalesMonitoring\Service;

class SlackWebhook
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $http;

    public function __construct()
    {
        $this->http = new \GuzzleHttp\Client([
            'timeout'  => 30,
            'allow_redirects' => true,
        ]);
    }

    public function post(string $webhookUrl, string $text, string $channel = null, string $color = null)
    {
        $data = [];

        if ($channel) {
            $data['channel'] = $channel;
        }

        if ($color) {
            $data['attachments'] = [[
                'color' => $color,
                'text' => $text,
                'link_names' => 1,
            ]];
        } else {
            $data['text'] = $text;
        }

        $this->http->post($webhookUrl, [
            'json' => $data
        ]);
    }
}