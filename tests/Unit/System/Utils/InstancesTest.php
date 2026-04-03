<?php

declare(strict_types=1);

namespace Tests\Unit\System\Utils;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../../System/Utils/Helpers.php';
require_once __DIR__ . '/../../../../System/Utils/Instances.php';

class InstancesTest extends TestCase
{
    public function testExpectedUtilityFunctionsAreDefined(): void
    {
        $this->assertTrue(function_exists('config'));
        $this->assertTrue(function_exists('router'));
        $this->assertTrue(function_exists('request'));
        $this->assertTrue(function_exists('response'));
        $this->assertTrue(function_exists('bot'));
        $this->assertTrue(function_exists('session'));
        $this->assertTrue(function_exists('queue'));
        $this->assertTrue(function_exists('database'));
        $this->assertTrue(function_exists('cache'));
        $this->assertTrue(function_exists('http'));
        $this->assertTrue(function_exists('event'));
        $this->assertTrue(function_exists('runtime'));
        $this->assertTrue(function_exists('logger'));
        $this->assertTrue(function_exists('services'));
        $this->assertTrue(function_exists('throttle'));
    }

    public function testHttpHelperCreatesConfiguredClient(): void
    {
        $client = http(['timeout' => 1.5]);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame(1.5, $client->getConfig('timeout'));
    }
}
