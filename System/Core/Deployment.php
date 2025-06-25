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

class Deployment
{

    /**
     * run git pull
     *
     * @return void
     */
    public static function run(): void
    {
        response()->close();
        if (!self::verify()) return;

        $event = request()->json();

        $message = $event['head_commit']['message'];
        $committer = $event['head_commit']['committer']['username'];
        $allowedUsers = explode(',', env('GIT_COMMIT_USERS'));
        $allowedKeywords = str_replace(',', '|', env('GIT_COMMIT_KEYWORDS'));

        if (in_array($committer, $allowedUsers)) {
            if (preg_match("/(?<=\s)#({$allowedKeywords})\b/i", $message)) {
                $gitPath = env('GIT_PATH');
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
        $signature = request()->headers('X-Hub-Signature-256');
        if (empty($signature) || empty(request()->json())) {
            return false;
        }

        $secret = env('GIT_WEBHOOK_SECRET');
        if (str_contains($signature, 'sha256=')) {
            $signature = explode('=', $signature)[1];
        }

        $hash = hash_hmac('sha256', json_encode(request()->json(), JSON_UNESCAPED_SLASHES), $secret);
        return hash_equals($hash, $signature);
    }

}