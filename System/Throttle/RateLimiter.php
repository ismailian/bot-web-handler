<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2026 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Throttle;

use Closure;
use TeleBot\System\Core\Enums\DataSource;
use TeleBot\System\Throttle\Drivers\RedisDriver;
use TeleBot\System\Throttle\Drivers\DatabaseDriver;
use TeleBot\System\Throttle\Drivers\FilesystemDriver;

class RateLimiter
{

    /** @var RateLimiterDriver $store data store */
    private readonly RateLimiterDriver $store;

    /** @var RateLimitRule[] */
    private array $rules = [];

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->store = match (env('THROTTLE_DRIVER', DataSource::FILESYSTEM)) {
            DataSource::DATABASE => new DatabaseDriver(),
            DataSource::REDIS => new RedisDriver(),
            default => new FilesystemDriver(),
        };
    }

    /**
     * Add a global rule (applies to every request).
     */
    public function addGlobalRule(int $maxRequests, int $windowSeconds, ?\Closure $keyResolver = null): static
    {
        $this->rules[] = new RateLimitRule($maxRequests, $windowSeconds, null, $keyResolver);
        return $this;
    }

    /**
     * Add a rule scoped to a specific route (See routes section in README.md)
     *
     * Examples:
     *   addRouteRule('POST /api/login', 5, 60)
     *   addRouteRule('POST /api/user/{userId}', 30, 60)
     *   addRouteRule('GET /api/user/{userId}/comments', 30, 60)
     */
    public function addRouteRule(
        string    $route,
        int       $maxRequests,
        int       $windowSeconds,
        ?\Closure $keyResolver = null
    ): static
    {
        $this->rules[] = new RateLimitRule($maxRequests, $windowSeconds, $route, $keyResolver);
        return $this;
    }

    /**
     * Check the current request against all matching rules.
     *
     * @return RateLimitResult[] Results for each matching rule.
     * @throws RateLimitExceededException when any rule is breached (and $throw is true).
     */
    public function check(
        ?string $uri = null,
        ?string $clientIp = null,
        bool    $throw = true,
        bool    $applyHeaders = true,
    ): array
    {
        $results = [];
        foreach ($this->rules as $index => $rule) {
            if (!$this->ruleMatchesRoute($rule)) {
                continue;
            }

            $key = $this->buildKey($rule, ($uri ?? request()->uri()), ($clientIp ?? $this->clientIp()));
            $result = $this->evaluate($key, $rule);
            $results[] = $result;

            $this->rules[$index]->usage = $result;

            if ($applyHeaders) {
                $result->applyHeaders();
            }

            if (!$result->allowed && $throw) {
                throw new RateLimitExceededException($result);
            }
        }

        return $results;
    }

    /**
     * Non-throwing convenience wrapper – returns false when limited.
     *
     * @param string|null $route
     * @param string|null $clientIp
     * @param bool $applyHeaders
     * @return RateLimitResult|bool
     */
    public function attempt(?string $route = null, ?string $clientIp = null, bool $applyHeaders = true): RateLimitResult|bool
    {
        try {
            $this->check($route, $clientIp, applyHeaders: $applyHeaders);
            return true;
        } catch (RateLimitExceededException $e) {
            return $e->result;
        }
    }

    /**
     * Reset all counters for a given route + IP combination.
     *
     * @param string|null $uri request uri
     * @param string|null $clientIp client IP
     * @return void
     */
    public function reset(?string $uri = null, ?string $clientIp = null): void
    {
        $clientIp = $clientIp ?? $this->clientIp();

        foreach ($this->rules as $rule) {
            if (!$this->ruleMatchesRoute($rule)) {
                continue;
            }
            $key = $this->buildKey($rule, $uri, $clientIp);
            $this->store->reset($key);
        }
    }

    /**
     * Get rate limit result object
     *
     * @param string $key
     * @param RateLimitRule $rule
     * @return RateLimitResult
     */
    private function evaluate(string $key, RateLimitRule $rule): RateLimitResult
    {
        $count = $this->store->increment($key, $rule->windowSeconds);
        $ttl = $this->store->ttl($key);
        $remaining = max(0, $rule->maxRequests - $count);
        $resetAt = time() + $ttl;
        $allowed = $count <= $rule->maxRequests;

        return new RateLimitResult(
            allowed: $allowed,
            limit: $rule->maxRequests,
            remaining: $remaining,
            resetAt: $resetAt,
            retryAfter: $allowed ? 0 : $ttl,
        );
    }

    /**
     * Check route if it matches current url
     *
     * @param RateLimitRule $rule
     * @return bool
     */
    private function ruleMatchesRoute(RateLimitRule $rule): bool
    {
        if ($rule->route === null) {
            return true;
        }

        return (bool)router()->matches([$rule->route => '*::*']);
    }

    /**
     * Build an identity key
     *
     * @param RateLimitRule $rule throttling rule
     * @param string $uri request uri
     * @param string $clientIp client IP
     * @return string
     */
    private function buildKey(RateLimitRule $rule, string $uri, string $clientIp): string
    {
        if ($rule->keyResolver !== null) {
            $suffix = ($rule->keyResolver)($uri, $clientIp);
        } else {
            $suffix = $clientIp;
        }

        $routePart = $rule->route ?? '__global__';
        return "rate_limit:{$routePart}:{$suffix}";
    }

    /**
     * Get client IP
     *
     * @return string
     */
    private function clientIp(): string
    {
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $header) {
            if (!empty($_SERVER[$header])) {
                // X-Forwarded-For can be a comma-separated list; take the first
                return trim(explode(',', $_SERVER[$header])[0]);
            }
        }
        return '0.0.0.0';
    }

    /**
     * Call a handler or throw a 429 response
     *
     * @param array|null $throttle throttle config
     * @param RateLimitResult|null $result rate-limit result
     * @return void
     */
    public function throwResponse(?array $throttle = null, ?RateLimitResult $result = null): void
    {
        if (!empty($throttle['handler']) && $throttle['handler'] instanceof Closure) {
            call_user_func($throttle['handler'], $result);
            return;
        }

        response()->setStatusCode(429)->end();
    }
}