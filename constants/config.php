<?php

namespace Constants;

use Monolog\Level;

class Config {
    const LOG_LEVEL = Level::Debug;
    const LOG_PATH = "/var/log/wordpress/sync.log";
    const POST_TYPE = ['post'];
}