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
use TeleBot\System\Interfaces\IValidator;

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
     * @param IValidator|null $validator
     * @return Catchable|BotApi
     */
    public function on(int $code, callable $callback, IValidator $validator = null): self
    {
        $this->callbacks["$code"] = $callback;
        if ($validator !== null) {
            $this->callbacks["$code"] = [
                'validator' => $validator,
                'callback' => $callback,
            ];
        }

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

        $callback = $this->callbacks["$code"];
        if (is_array($callback)) {
            $validator = $callback['validator'];
            $callback = $callback['callback'];
            if (!$validator->isValid(
                is_array($data) ? json_encode($data) : $data)
            ) return;
        }

        $callback($data);
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