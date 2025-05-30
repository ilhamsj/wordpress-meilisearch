<?php

namespace Utils;

use Constants\Config;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;

class Logger {

    private $monolog;

    public function __construct($name) {
        $this->monolog = new MonologLogger($name);

        $stream = new StreamHandler('php://stdout', Config::LOG_LEVEL);
        $this->monolog->pushHandler($stream);

        $file = new RotatingFileHandler(Config::LOG_PATH, 7, Config::LOG_LEVEL);
        $this->monolog->pushHandler($file);
    }

    private function log(Level $level, string $message, array $context = []) {
        $this->monolog->log($level, $message, $context);
    }

    public function info($message, $context = []) {
        $this->log(Level::Info, $message, $context);
    }

    public function error($message, $context = []) {
        $this->log(Level::Error, $message, $context);
    }

    public function debug($message, $context = []) {
        $this->log(Level::Debug, $message, $context);
    }

    public function warning($message, $context = []) {
        $this->log(Level::Warning, $message, $context);
    }
}