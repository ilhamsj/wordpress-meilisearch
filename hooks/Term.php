<?php

namespace Hooks;

use Constants\Config;
use Constants\MeilisearchConfig;
use Utils\ApiCall;
use Utils\Logger;

class Term {

    private $logger;
    private $apiCall;
    
    public function __construct() {
        $this->logger = new Logger(__CLASS__);
        $this->apiCall = new ApiCall(MeilisearchConfig::getIndexTerm());

        add_action('created_term', [$this, 'handle_created_term'], 10, 3);
        add_action('edited_term', [$this, 'handle_created_term'], 10, 3);
        add_action('delete_term', [$this, 'handle_deleted_term'], 10, 3);
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
            'description' => $term->description,
            'parent' => $term->parent,
            'count' => $term->count,
        );
        
        $this->apiCall->send('term', $term_data);
    }

    public function handle_deleted_term($term_id, $tt_id, $taxonomy) {
        $this->logger->info(__FUNCTION__, ['term_id' => $term_id, 'tt_id' => $tt_id, 'taxonomy' => $taxonomy]);
        $this->apiCall->delete($term_id);
    }
}