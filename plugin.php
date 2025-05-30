<?php
/**
 * Plugin Name: WordPress Custom Sync
 * Description: Triggers external webhook when posts or taxonomies are created, updated, deleted, or when post statuses change.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: wordpress-custom-sync
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
