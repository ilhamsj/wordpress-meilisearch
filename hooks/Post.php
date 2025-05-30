<?php

namespace Hooks;

use Constants\Config;
use Constants\MeilisearchConfig;
use Services\Response;
use Utils\ApiCall;
use Utils\Logger;
use WP_Post;

class Post {

    private $logger;
    private $apiCall;
    private $response;
    
    public function __construct() {
        $this->logger = new Logger(__CLASS__);
        $this->apiCall = new ApiCall(MeilisearchConfig::getIndexPost());
        $this->response = new Response();

        add_action('save_post', [$this, 'handle_save_post'], 10, 3);
        add_action('wp_trash_post', [$this, 'handle_delete_post'], 10, 3);
    }

    public function handle_transition_post_status($new_status, $old_status, $post) {

        if ($post->post_type !== 'post') return;

        $is_new_status_published = $new_status === 'publish';
        $is_old_status_published = $old_status === 'publish';

        $response = [
            'action' => null,
            'post' => $post,
        ];

        if ($is_new_status_published && !$is_old_status_published) {
            $response['action'] = 'create';
            $this->logger->info(__FUNCTION__, $response);
        }

        if ($is_new_status_published && $is_old_status_published) {
            $response['action'] = 'update';
            $this->logger->info(__FUNCTION__, $response);
        }

        if (!$is_new_status_published && $is_old_status_published) {
            $response['action'] = 'delete';
            $this->logger->info(__FUNCTION__, $response);
        }
    }

    public function handle_save_post(int $post_id, WP_Post $post, bool $update) {
        if (!in_array($post->post_type, Config::POST_TYPE)) return;

        $post_data = [];
        $post_data = $this->response->default($post_id);
        
        if($update && $post->post_status === 'publish') {
            $this->logger->info(__FUNCTION__, $post_data);
            $this->apiCall->send('post', $post_data);
        }
    }

    public function handle_delete_post(int $post_id) {
        $this->logger->info(__FUNCTION__, ['post_id' => $post_id]);
        $this->apiCall->delete($post_id);
    }
}