<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core;

use TeleBot\System\Http\Request;
use TeleBot\System\Http\Response;

class Deployment
{

    /**
     * run git pull
     *
     * @return void
     */
    public static function run(): void
    {
        Response::close();
        if (!self::verify()) return;

        $event = Request::json();

        $message = $event['head_commit']['message'];
        $committer = $event['head_commit']['committer']['username'];
        $allowedUsers = explode(',', getenv('GIT_COMMIT_USERS'));
        $allowedKeywords = str_replace(',', '|', getenv('GIT_COMMIT_KEYWORDS'));

        if (in_array($committer, $allowedUsers)) {
            if (preg_match("/(?<=\s)#({$allowedKeywords})\b/i", $message)) {
                $gitPath = getenv('GIT_PATH', true);
                Process::run("{$gitPath} pull");
            }
        }
    }

    /**
     * verify the authenticity of the payload
     *
     * @return bool
     */
    protected static function verify(): bool
    {
        $signature = Request::headers('X-Hub-Signature-256');
        if (empty($signature) || empty(Request::json())) {
            return false;
        }

        $secret = getenv('GIT_WEBHOOK_SECRET', true);
        if (str_contains($signature, 'sha256=')) {
            $signature = explode('=', $signature)[1];
        }

        $hash = hash_hmac('sha256', json_encode(Request::json(), JSON_UNESCAPED_SLASHES), $secret);
        return hash_equals($hash, $signature);
    }

}