<?php

namespace Utils;

use Constants\MeilisearchConfig;

class ApiCall {
    private $logger;
    private $url;
    private $headers;

    public function __construct(string $index) { 
        $this->logger = new Logger(__CLASS__);
        $this->url = sprintf('%s/indexes/%s/documents', MeilisearchConfig::getUrl(), $index);
        $this->headers = [
            'Authorization' => sprintf('Bearer %s', MeilisearchConfig::getApiKey()),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Add documents to the Meilisearch index
     * 
     * @param string $object_type The type of object being indexed (for logging)
     * @param array $data The document data to be indexed
     * @return void
     */
    public function send($object_type, $data) {
        $response = wp_remote_post($this->url, [
            'method'    => 'POST',
            'timeout'   => 10,
            'body'      => json_encode($data),
            'headers'   => $this->headers,
        ]);

        $this->handleResponse($response, __FUNCTION__, ['data' => $data]);
    }

    /**
     * Delete a document from the Meilisearch index by ID
     * 
     * @param string|int $id The document ID to delete
     * @return void
     */
    public function delete($id) {
        $response = wp_remote_post(sprintf('%s/%s', $this->url, $id), [
            'method'    => 'DELETE',
            'timeout'   => 10,
            'headers'   => $this->headers,
        ]);

        $this->handleResponse($response, __FUNCTION__, ['id' => $id]);
    }
    
    /**
     * Process API response and handle errors
     * 
     * @param mixed $response The response from wp_remote_post
     * @param string $method The calling method name
     * @param array $context Additional context for logging
     * @return void
     */
    private function handleResponse($response, $method, $context = []) {
        if (is_wp_error($response)) {
            $this->logger->error($method, array_merge(['response' => $response->get_error_message()], $context));
        } else {
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $this->logger->info($method, array_merge(['response' => $response_body], $context));
        }
    }
}
