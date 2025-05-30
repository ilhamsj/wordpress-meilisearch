<?php

namespace Constants;

use Admin\MeilisearchDashboard;

class MeilisearchConfig {
    // Default Meilisearch constants (used as fallback)
    const DEFAULT_URL = "http://meilisearch:7700";
    const DEFAULT_INDEX_POST = "wordpress-posts";
    const DEFAULT_INDEX_TERM = "wordpress-terms";
    const DEFAULT_API_KEY = "4kI11feArYtw72TT_tu7w_SWCCg8CiXF-mR7w8jV80U";

    // Dynamic properties that use wp_options with fallbacks to constants
    public static function getUrl() {
        $options = get_option(MeilisearchDashboard::OPTION_NAME, []);
        return isset($options['url']) && !empty($options['url']) 
            ? $options['url'] 
            : self::DEFAULT_URL;
    }

    public static function getIndexPost() {
        $options = get_option(MeilisearchDashboard::OPTION_NAME, []);
        return isset($options['index_post']) && !empty($options['index_post']) 
            ? $options['index_post'] 
            : self::DEFAULT_INDEX_POST;
    }

    public static function getIndexTerm() {
        $options = get_option(MeilisearchDashboard::OPTION_NAME, []);
        return isset($options['index_term']) && !empty($options['index_term']) 
            ? $options['index_term'] 
            : self::DEFAULT_INDEX_TERM;
    }

    public static function getApiKey() {
        $options = get_option(MeilisearchDashboard::OPTION_NAME, []);
        return isset($options['api_key']) && !empty($options['api_key']) 
            ? $options['api_key'] 
            : self::DEFAULT_API_KEY;
    }
} 