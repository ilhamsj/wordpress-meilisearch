<?php

namespace Hooks;

use Utils\ApiCall;

class Term {
    private $apiCall;
    
    public function __construct() {
        $this->apiCall = new ApiCall();

        add_action('created_term', [$this, 'handle_created_term'], 10, 3);
        add_action('edited_term', [$this, 'handle_created_term'], 10, 3);
        add_action('deleted_term', [$this, 'handle_deleted_term'], 10, 3);
        add_action('trashed_post', [$this, 'handle_trashed_post'], 10, 3);
        add_action('untrashed_post', [$this, 'handle_untrashed_post'], 10, 3);
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
        
        $this->apiCall->send('term', $term_data);
    }

    public function handle_deleted_term($term_id, $tt_id, $taxonomy) {
        $term = get_term($term_id, $taxonomy);
        
        $term_data = array(
            'term_id' => $term_id,
            'taxonomy' => $taxonomy,
            'name' => $term->name,
            'slug' => $term->slug,
            'event_type' => 'delete'
        );
        
        $this->apiCall->send('term', $term_data);
    }
}