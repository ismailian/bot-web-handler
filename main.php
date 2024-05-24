<?php

/**
 * load composer packages
 */
require_once 'vendor/autoload.php';

/**
 * import handler class
 */
use TeleBot\System\AppHandler;
use TeleBot\System\ExceptionHandler;

set_exception_handler(fn($e) => ExceptionHandler::onException($e));
set_error_handler(fn(...$args) => ExceptionHandler::onError(...$args));

/**
 * create and run instance
 */
(new AppHandler())->start();