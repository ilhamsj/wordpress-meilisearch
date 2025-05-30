<?php

namespace Constants;

use Monolog\Level;

class Config {
    const WEBHOOK_URL = "http://host.docker.internal/n8n/webhook/wordpress";
    const LOG_LEVEL = Level::Debug;
    const LOG_PATH = "/var/log/wordpress/sync.log";
    const POST_TYPE = ['post'];
    const MEILISEARCH_URL = "http://meilisearch:7700";
    const MEILISEARCH_INDEX_POST = "wordpress-posts";
    const MEILISEARCH_INDEX_TERM = "wordpress-terms";
    const MEILISEARCH_API_KEY = "4kI11feArYtw72TT_tu7w_SWCCg8CiXF-mR7w8jV80U";
}