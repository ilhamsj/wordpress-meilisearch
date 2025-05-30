<?php
/**
 * Plugin Name: WordPress Custom Sync
 * Description: Triggers external webhook when posts or taxonomies are created, updated, deleted, or when post statuses change.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: wordpress-custom-sync
 */

require_once __DIR__ . '/utils/api-call.php';

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WordPress_Custom_Sync {
    private $api_call;
    
    public function __construct() {
        $this->api_call = new Api_Call();

        add_action('created_term', [$this, 'handle_created_term'], 10, 3);
    }
    
    /**
     * Handle term creation
     */
    public function handle_created_term($term_id, $tt_id, $taxonomy) {
        $term = get_term($term_id, $taxonomy);
        
        $term_data = array(
            'term_id' => $term_id,
            'taxonomy' => $taxonomy,
            'name' => $term->name,
            'slug' => $term->slug,
            'event_type' => 'create'
        );
        
        $this->api_call->send('term', $term_data);
    }
}

new WordPress_Custom_Sync(); 