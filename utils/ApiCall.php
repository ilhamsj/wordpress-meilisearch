<?php

namespace Utils;
class ApiCall {
    private $webhook_url = "http://host.docker.internal/n8n/webhook/wordpress";

    /**
     * Send webhook to configured URL
     */
    public function send($object_type, $data) {
        if (empty($this->webhook_url)) {
            error_log('Webhook URL is not set');
            return;
        }

        $response = wp_remote_post($this->webhook_url, [
            'method'    => 'POST',
            'timeout'   => 10,
            'body'      => $data,
        ]);

        if (is_wp_error($response)) {
            error_log('API error: ' . $response->get_error_message());
        } else {
            error_log('API success: ' . wp_remote_retrieve_body($response));
        }
    }
}
