<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\Config;

/**
 * @var array $config configuration properties
 */
return [

    /**
     * @var string $domain application domain name
     */
    'domain' => getenv('APP_DOMAIN', true),

    /**
     * @param string $token property holding bot token
     */
    'token' => getenv('TG_BOT_TOKEN', true),

    /**
     * @var string $ip property holding source IP
     */
    'ip' => getenv('TG_WEBHOOK_SOURCE_IP', true),

    /**
     * @var array $routes allowed routes
     */
    'routes' => [],

    /**
     * @var array $authorization property holding request signature
     */
    'signature' => getenv('TG_WEBHOOK_SIGNATURE', true),

    /**
     * @var array $admins list of bot admins
     * beneficial for allowing some users elevated access
     */
    'admins' => [],

    /**
     * @var array $users allowed users
     */
    'users' => [

        /**
         * whitelisted users. Leave empty to accept all
         */
        'whitelist' => [],

        /**
         * blacklisted users. Leave empty to accept all
         * this will be overlooked if *whitelist* is not empty
         */
        'blacklist' => [],
    ],

    /**
     * @var string $fallback fallback property for unhandled telegram events
     */
    'fallback' => null,

];