<?php
/**
 * Plugin Name: WordPress Meilisearch
 * Description: Sync WordPress posts and taxonomies to Meilisearch.
 * Version: 1.0.0
 * Author: @ilhamsj
 * Text Domain: wordpress-meilisearch
 */

use Hooks\Post;
use Hooks\Term;
use Admin\MeilisearchDashboard;

require __DIR__ . '/vendor/autoload.php';

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Initialize hooks
new Term();
new Post();

// Initialize admin dashboard (only in admin area)
new MeilisearchDashboard();
