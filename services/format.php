<?php

namespace Services;

class Format {

    /**
     * Get author data
     *
     * @param int $author_id Author ID
     * @return array Author data
     */
    public function get_author_detail(int $author_id): array {
        return [
            'id' => $author_id,
            'name' => get_the_author_meta('display_name', $author_id),
            'avatar' => get_avatar_url($author_id),
            'description' => get_the_author_meta('description', $author_id),
            'slug' => get_the_author_meta('user_nicename', $author_id),
        ];
    }
    
    /**
     * Get taxonomy term names
     *
     * @param string $taxonomy Taxonomy name ('category' or 'post_tag')
     * @param int $post_id Post ID
     * @return array Array of term names
     */
    public function get_taxonomy_terms(string $taxonomy, int $post_id): array {

        switch ($taxonomy) {
            case 'category':
                $terms = get_the_category($post_id);
                break;
            case 'post_tag':
                $terms = get_the_tags($post_id);
                break;
            default:
                $terms = [];
                break;
        }

        if (empty($terms)) return [];

        return array_map(function($term) {
            return $term->name ?? '';
        }, $terms);
    }
    
    /**
     * Get taxonomy term details
     *
     * @param string $taxonomy Taxonomy name ('category' or 'post_tag')
     * @param int $post_id Post ID
     * @return array Array of term details
     */
    public function get_taxonomy_details(string $taxonomy, int $post_id): array {

        switch ($taxonomy) {
            case 'category':
                $terms = get_the_category($post_id);
                break;
            case 'post_tag':
                $terms = get_the_tags($post_id);
                break;
            default:
                $terms = [];
                break;
        }

        if (empty($terms)) return [];

        return array_map(function($term) { 
            return [
                'id' => $term->term_id,
                'slug' => $term->slug,
                'title' => $term->name,
            ]; 
        }, $terms);
    }
    
    /**
     * Get featured media data
     *
     * @param int $post_id Post ID
     * @return array|null Featured media data or null if not set
     */
    public function get_featured_media(int $post_id): ?array {
        $thumb_id = get_post_thumbnail_id($post_id);
        if (!$thumb_id) return null;
        
        return [
            'id' => $thumb_id,
            'url' => wp_get_attachment_url($thumb_id),
            'alt' => get_post_meta($thumb_id, '_wp_attachment_image_alt', true)
        ];
    }
    
    /**
     * Get SEO data
     *
     * @param int $post_id Post ID
     * @return array SEO data
     */
    public function get_seo_data(int $post_id): array {
        $featured_media = $this->get_featured_media($post_id);

        return [
            'meta_title' => get_post_meta($post_id, '_yoast_wpseo_title', true) ?: get_the_title($post_id),
            'meta_description' => get_post_meta($post_id, '_yoast_wpseo_metadesc', true) ?: get_the_excerpt($post_id),
            'og_image' => $featured_media['url'] ?? null
        ];
    }
}