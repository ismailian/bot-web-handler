<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Cache;

use TeleBot\System\Core\Traits\Expirable;
use TeleBot\System\Interfaces\ICacheDriver;
use TeleBot\System\Cache\Drivers\{FileDriver, RedisDriver};

class Cache
{

    use Expirable;

    /** @var ICacheDriver|null $client client */
    protected ?ICacheDriver $client = null;

    /**
     * initialize driver client
     *
     * @return ICacheDriver
     */
    protected function init(): ICacheDriver
    {
        if ($this->client === null) {
            $driver = env('CACHE_DRIVER', 'filesystem');
            $this->client = match ($driver) {
                'redis' => new RedisDriver(),
                'filesystem' => new FileDriver(),
            };
        }

        return $this->client;
    }

    /**
     * get request fingerprint
     *
     * @return string
     */
    public function fingerprint(): string
    {
        $ip = request()->ip();
        $uri = request()->uri();
        $method = request()->method();

        return md5("$ip|$method|$uri");
    }

    /**
     * get cached data
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $data = $this->init()->read($key);
        if ($this->isExpired($data)) {
            $this->forget($key);
            return null;
        }

        $data = $this->restore($data);

        unset($data[$this->expireKey]);
        return $data;
    }

    /**
     * set data
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $expires
     * @return bool
     */
    public function remember(string $key, mixed $value, ?string $expires = null): bool
    {
        $this->addExpireTimestamp($value, $expires);

        return $this->init()->write($key, $value);
    }

    /**
     * clear cache
     *
     * @param string $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->init()->delete($key);
    }

}