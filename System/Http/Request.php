<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Http;

class Request
{

    /** @var array $headers request headers */
    protected array $_headers = [];

    /** @var array $_json parsed JSON body */
    protected array $_json = [];

    /** @var string $_rawBody raw request body */
    protected string $_rawBody = '';

    public function __construct()
    {
        foreach (getallheaders() as $key => $value) {
            $this->_headers[trim(strtolower($key))] = $value;
        }

        $this->_rawBody = file_get_contents('php://input') ?: '';
        try {
            $this->_json = $this->_rawBody !== ''
                ? json_decode($this->_rawBody, true, 512, JSON_THROW_ON_ERROR)
                : [];
        } catch (\JsonException) {
            $this->_json = [];
        }
    }

    /**
     * Get header
     *
     * @param string $key
     * @return string|null
     */
    public function header(string $key): ?string
    {
        return $this->headers()[strtolower(trim($key))] ?? null;
    }

    /**
     * Return request headers
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->_headers;
    }

    /**
     * Get source IP address.
     *
     * By default, returns the direct connection IP (REMOTE_ADDR).
     *
     * Pass $trustProxy = true only if Nginx is explicitly configured to set
     * one of the proxy headers below (e.g. via proxy_set_header). Without
     * that Nginx config in place, these headers can be freely forged by the
     * client and MUST NOT be trusted.
     *
     * Headers are checked in priority order:
     *   1. CF-Connecting-IP  (Cloudflare)
     *   2. X-Real-IP         (Nginx)
     *   3. X-Forwarded-For   (standard; leftmost IP is used)
     *   4. REMOTE_ADDR       (fallback)
     *
     * @param bool $trustProxy
     * @return string
     */
    public function ip(bool $trustProxy = false): string
    {
        if ($trustProxy) {
            if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                return trim($_SERVER['HTTP_CF_CONNECTING_IP']);
            }

            if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
                return trim($_SERVER['HTTP_X_REAL_IP']);
            }

            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // X-Forwarded-For may contain a comma-separated chain; take the leftmost entry,
                // which is the original client IP as reported by the first proxy in the chain.
                // Only use this when you are certain Nginx is setting this header server-side.
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip  = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Get host name
     *
     * @return string|null
     */
    public function origin(): ?string
    {
        return $_SERVER['HTTP_ORIGIN'] ?? null;
    }

    /**
     * Get request URI (without query string).
     *
     * Uses REDIRECT_URL when available (Apache mod_rewrite),
     * otherwise falls back to REQUEST_URI and strips the query string.
     *
     * @return string
     */
    public function uri(): string
    {
        if (array_key_exists('REDIRECT_URL', $_SERVER)) {
            return $_SERVER['REDIRECT_URL'];
        }

        $requestUri = $_SERVER['REQUEST_URI'];
        if (str_contains($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, strpos($requestUri, '?'));
        }

        return $requestUri;
    }

    /**
     * Get request method (lowercase)
     *
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Check if request method matches the provided one
     *
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return $this->method() === strtolower($method);
    }

    /**
     * Get query parameters
     *
     * @param string|null $key
     * @param bool $raw
     * @return string|array|null
     */
    public function query(?string $key = null, bool $raw = false): string|array|null
    {
        if ($raw) {
            return $_SERVER['QUERY_STRING'] ?? '';
        }

        if ($key !== null) {
            return $_GET[$key] ?? null;
        }

        return $_GET;
    }

    /**
     * Get form-data body
     *
     * @param string|null $key
     * @param bool $raw
     * @return array|string|null
     */
    public function body(?string $key = null, bool $raw = false): array|string|null
    {
        if ($raw) {
            return $this->_rawBody;
        }

        if ($key !== null) {
            return $_POST[$key] ?? null;
        }

        return $_POST;
    }

    /**
     * Get full JSON body or a single value by key
     *
     * @param string|null $key
     * @param bool $raw
     * @return array|string|null
     */
    public function json(?string $key = null, bool $raw = false): array|string|null
    {
        if ($raw) {
            return $this->_rawBody;
        }

        if ($key !== null) {
            return $this->_json[$key] ?? null;
        }

        return $this->_json;
    }

    /**
     * Get request fingerprint
     *
     * @param bool $includeBody include body signature in the fingerprint
     * @return string
     */
    public function fingerprint(bool $includeBody = false): string
    {
        $query = $this->query();
        ksort($query);

        $segments = [
            $this->ip(),
            $this->uri(),
            $this->method(),
            md5(http_build_query($query)),
        ];

        if ($includeBody) {
            $body = $this->body();
            ksort($body);
            $segments[] = md5(http_build_query($body));

            $json = $this->_json;
            ksort($json);
            $segments[] = md5(json_encode($json));
        }

        return md5(join('|', $segments));
    }

}