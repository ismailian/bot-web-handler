<?php

/**
 * load composer packages
 */
require_once 'vendor/autoload.php';

/**
 * import handler class
 */
use TeleBot\System\BotHandler;

/**
 * create and run instance
 */
$h = new BotHandler();
$h->start();