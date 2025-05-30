<?php

namespace Utils;

use Constants\Config;

class ApiCall {
    private $logger;
    private $webhook_url = Config::WEBHOOK_URL;

    public function __construct() {
        $this->logger = new Logger(__CLASS__);
    }

    /**
     * Send webhook to configured URL
     */
    public function send($object_type, $data) {
        if (empty($this->webhook_url)) {
            $this->logger->error('Webhook URL is not set');
            return;
        }

        $response = wp_remote_post($this->webhook_url, [
            'method'    => 'POST',
            'timeout'   => 10,
            'body'      => $data,
        ]);

        if (is_wp_error($response)) {
            $this->logger->error('API error', ['response' => $response->get_error_message(), 'data' => $data]);
        } else {
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $this->logger->info('API success', ['response' => $response_body, 'data' => $data]);
        }
    }
}
