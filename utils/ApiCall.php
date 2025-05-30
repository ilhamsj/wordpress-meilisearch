<?php

namespace Utils;

use Constants\Config;

class ApiCall {
    private $logger;
    private $url;

    public function __construct(string $index) { 
        $this->logger = new Logger(__CLASS__);
        $this->url = sprintf('%s/indexes/%s/documents', Config::MEILISEARCH_URL, $index);
    }

    /**
     * Send webhook to configured URL
     */
    public function send($object_type, $data) {

        $headers = [
            'Authorization' => sprintf('Bearer %s', Config::MEILISEARCH_API_KEY),
            'Content-Type' => 'application/json',
        ];

        $response = wp_remote_post($this->url, [
            'method'    => 'POST',
            'timeout'   => 10,
            'body'      => json_encode($data),
            'headers'   => $headers,
        ]);

        if (is_wp_error($response)) {
            $this->logger->error('API error', ['response' => $response->get_error_message(), 'data' => $data]);
        } else {
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $this->logger->info('API success', ['response' => $response_body, 'data' => $data]);
        }
    }
}
