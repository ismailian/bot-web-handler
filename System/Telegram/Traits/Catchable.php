<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Traits;

use Closure;
use TeleBot\System\Telegram\BotApi;

trait Catchable
{

    /**
     * @var array callbacks to invoke upon http errors
     */
    protected array $callbacks = [];

    /** @var Closure|null $errorHandler closure to handle too exceptions */
    protected Closure|null $errorHandler = null;

    /**
     * handle error exception
     *
     * @param int $code
     * @param callable $callback
     * @return Catchable|BotApi
     */
    public function on(int $code, callable $callback): self
    {
        $this->callbacks["$code"] = $callback;

        return $this;
    }

    /**
     * catch all http errors
     *
     * @param callable $callback
     * @return Catchable|BotApi
     */
    public function catch(callable $callback): self
    {
        $this->errorHandler = $callback;

        return $this;
    }

    /**
     * resolve a http exception to a callback
     *
     * @param int $code
     * @param mixed $data
     * @return void
     */
    private function resolve(int $code, mixed $data): void
    {
        if (!array_key_exists("$code", $this->callbacks)) {
            return;
        }

        $this->callbacks["$code"]($data);
    }

    /**
     * pass exceptions to a custom error handler
     *
     * @param $exception
     * @return void
     */
    private function throw($exception): void
    {
        if ($this->errorHandler) {
            call_user_func($this->errorHandler, $exception);
        }
    }

}