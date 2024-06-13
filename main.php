<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'vendor/autoload.php';

use TeleBot\System\AppHandler;
use TeleBot\System\ExceptionHandler;

set_exception_handler(fn($e) => ExceptionHandler::onException($e));
set_error_handler(fn(...$args) => ExceptionHandler::onError(...$args));

/** create and run instance */
(new AppHandler())->start();