<?php

namespace Hooks;

use Constants\MeilisearchConfig;
use Utils\Logger;
use Utils\MeilisearchClient;

class Term {

    private $logger;
    private $index;
    
    public function __construct() {
        $this->logger = new Logger(__CLASS__);
        $this->index = new MeilisearchClient(MeilisearchConfig::getIndexTerm());

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
        
        $this->index->addDocuments([$term_data]);
    }

    public function handle_deleted_term($term_id, $tt_id, $taxonomy) {
        $this->logger->info(__FUNCTION__, ['term_id' => $term_id, 'tt_id' => $tt_id, 'taxonomy' => $taxonomy]);
        $this->index->deleteDocument($term_id);
    }
}