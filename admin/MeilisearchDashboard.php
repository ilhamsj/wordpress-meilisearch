<?php

namespace Admin;

use Constants\MeilisearchConfig;

class MeilisearchDashboard {
    const OPTION_NAME = 'meilisearch_options';
    
    private $options;
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        
        $this->options = get_option(self::OPTION_NAME, [
            'url' => MeilisearchConfig::DEFAULT_URL,
            'api_key' => MeilisearchConfig::DEFAULT_API_KEY,
            'index_post' => MeilisearchConfig::DEFAULT_INDEX_POST,
            'index_term' => MeilisearchConfig::DEFAULT_INDEX_TERM,
        ]);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Meilisearch Settings',
            'Meilisearch',
            'manage_options',
            'meilisearch-settings',
            [$this, 'render_settings_page'],
            'dashicons-search',
            30
        );
    }
    
    public function register_settings() {
        register_setting(
            'meilisearch_settings',
            self::OPTION_NAME,
            [$this, 'sanitize_options']
        );
        
        add_settings_section(
            'meilisearch_settings_section',
            'Meilisearch Configuration',
            [$this, 'render_section_description'],
            'meilisearch-settings'
        );
        
        add_settings_field(
            'meilisearch_url',
            'Meilisearch URL',
            [$this, 'render_url_field'],
            'meilisearch-settings',
            'meilisearch_settings_section'
        );
        
        add_settings_field(
            'meilisearch_api_key',
            'API Key',
            [$this, 'render_api_key_field'],
            'meilisearch-settings',
            'meilisearch_settings_section'
        );
        
        add_settings_field(
            'meilisearch_index_post',
            'Posts Index Name',
            [$this, 'render_index_post_field'],
            'meilisearch-settings',
            'meilisearch_settings_section'
        );
        
        add_settings_field(
            'meilisearch_index_term',
            'Terms Index Name',
            [$this, 'render_index_term_field'],
            'meilisearch-settings',
            'meilisearch_settings_section'
        );
    }
    
    public function sanitize_options($input) {
        $sanitized = [];
        
        $sanitized['url'] = sanitize_text_field($input['url']);
        $sanitized['api_key'] = sanitize_text_field($input['api_key']);
        $sanitized['index_post'] = sanitize_text_field($input['index_post']);
        $sanitized['index_term'] = sanitize_text_field($input['index_term']);
        
        return $sanitized;
    }
    
    public function render_section_description() {
        echo '<p>Configure your Meilisearch settings for WordPress integration.</p>';
    }
    
    public function render_url_field() {
        printf(
            '<input type="text" id="meilisearch_url" name="%s[url]" value="%s" class="regular-text" />',
            self::OPTION_NAME,
            esc_attr($this->options['url'])
        );
    }
    
    public function render_api_key_field() {
        printf(
            '<input type="password" id="meilisearch_api_key" name="%s[api_key]" value="%s" class="regular-text" />',
            self::OPTION_NAME,
            esc_attr($this->options['api_key'])
        );
    }
    
    public function render_index_post_field() {
        printf(
            '<input type="text" id="meilisearch_index_post" name="%s[index_post]" value="%s" class="regular-text" />',
            self::OPTION_NAME,
            esc_attr($this->options['index_post'])
        );
    }
    
    public function render_index_term_field() {
        printf(
            '<input type="text" id="meilisearch_index_term" name="%s[index_term]" value="%s" class="regular-text" />',
            self::OPTION_NAME,
            esc_attr($this->options['index_term'])
        );
    }
    
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('meilisearch_settings');
                do_settings_sections('meilisearch-settings');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }
}