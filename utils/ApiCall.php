<?php

namespace Utils;

use Constants\Config;

class ApiCall {
    private $logger;
    private $url;
    private $headers = [];

    public function __construct(string $index) { 
        $this->logger = new Logger(__CLASS__);
        $this->url = sprintf('%s/indexes/%s/documents', Config::MEILISEARCH_URL, $index);
        $this->headers = [
            'Authorization' => sprintf('Bearer %s', Config::MEILISEARCH_API_KEY),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Send webhook to configured URL
     */
    public function send($object_type, $data) {
        $response = wp_remote_post($this->url, [
            'method'    => 'POST',
            'timeout'   => 10,
            'body'      => json_encode($data),
            'headers'   => $this->headers,
        ]);

        if (is_wp_error($response)) {
            $this->logger->error(__FUNCTION__, ['response' => $response->get_error_message(), 'data' => $data]);
        } else {
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $this->logger->info(__FUNCTION__, ['response' => $response_body, 'data' => $data]);
        }
    }

    /**
     * Send webhook to configured URL
     */
    public function delete($id) {

        $response = wp_remote_post(sprintf('%s/%s', $this->url, $id), [
            'method'    => 'DELETE',
            'timeout'   => 10,
            'headers'   => $this->headers,
        ]);

        if (is_wp_error($response)) {
            $this->logger->error(__FUNCTION__, ['response' => $response->get_error_message(), 'id' => $id]);
        } else {
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $this->logger->info(__FUNCTION__, ['response' => $response_body, 'id' => $id]);
        }
    }
}
