<?php

namespace Services;

class Response {

    /**
     * @var Format
     */
    private $format;

    public function __construct() {
        $this->format = new Format();
    }

    /**
     * Format post data for Meilisearch indexing
     *
     * @param int $post_id Post ID to format
     * @return array Formatted post data or empty array if invalid
     */
    public function default(int $post_id): array {
        $post = get_post($post_id);
        
        return [
            'id' => $post_id,
            'title' => get_the_title($post_id),
            'slug' => $post->post_name,
            'excerpt' => get_the_excerpt($post_id),
            'content' => apply_filters('the_content', $post->post_content),
            'status' => $post->post_status,
    
            'author' => $this->format->get_author_data($post->post_author),
            'categories' => $this->format->get_taxonomy_terms('category', $post_id),
            'categories_detail' => $this->format->get_taxonomy_details('category', $post_id),
            'tags' => $this->format->get_taxonomy_terms('post_tag', $post_id),
            'tags_detail' => $this->format->get_taxonomy_details('post_tag', $post_id),
            'featured_media' => $this->format->get_featured_media($post_id),
    
            'seo' => $this->format->get_seo_data($post_id),
    
            'created_at' => get_the_date(DATE_ATOM, $post_id),
            'updated_at' => get_the_modified_date(DATE_ATOM, $post_id)
        ];
    }
}