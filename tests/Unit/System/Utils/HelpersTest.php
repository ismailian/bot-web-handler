<?php

declare(strict_types=1);

namespace Tests\Unit\System\Utils;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../../System/Utils/Helpers.php';

class HelpersTest extends TestCase
{
    public function testDotResolvesArrayAndObjectPaths(): void
    {
        $data = [
            'user' => [
                'name' => 'Alice',
                'profile' => ['age' => 30],
            ],
        ];

        $this->assertSame('Alice', dot('user.name', $data));
        $this->assertSame(30, dot('user.profile.age', $data));
        $this->assertNull(dot('user.profile.country', $data));

        $object = new class {
            public object $user;

            public function __construct()
            {
                $this->user = new class {
                    public function getName(): string
                    {
                        return 'Bob';
                    }
                };
            }
        };

        $this->assertSame('Bob', dot('user.name', $object));
    }

    public function testEnvReadsDefaultAndBooleanValues(): void
    {
        putenv('UNIT_TEST_ENV_BOOL=true');
        putenv('UNIT_TEST_ENV_EMPTY=');

        $this->assertTrue(env('UNIT_TEST_ENV_BOOL'));
        $this->assertSame('fallback', env('UNIT_TEST_ENV_EMPTY', 'fallback'));
        $this->assertSame('fallback', env('UNIT_TEST_ENV_MISSING', 'fallback'));
    }

    public function testFileIdAndUrlHelpers(): void
    {
        $this->assertTrue(is_file_id('abcDEF-123'));
        $this->assertFalse(is_file_id('invalid/id'));

        $this->assertTrue(is_url('https://example.com/path'));
        $this->assertFalse(is_url('not-a-url'));
    }

    public function testGetBufferReturnsIdentifierForFileIdOrUrl(): void
    {
        $fileId = 'AABBCC-123';
        $url = 'https://example.com/image.jpg';

        $this->assertSame($fileId, get_buffer($fileId));
        $this->assertSame($url, get_buffer($url));
    }

    public function testIso8601HelpersReturnTimestampsOrNull(): void
    {
        $seconds = iso8601_to_seconds('PT1M');
        $timestamp = iso8601_to_timestamp('PT1M');

        $this->assertIsInt($seconds);
        $this->assertIsInt($timestamp);
        $this->assertGreaterThan(0, $seconds);
        $this->assertGreaterThan(time(), $timestamp);

        $this->assertNull(iso8601_to_seconds('invalid'));
        $this->assertNull(iso8601_to_timestamp('invalid'));
    }
}
