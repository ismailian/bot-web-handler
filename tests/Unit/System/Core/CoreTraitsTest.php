<?php

declare(strict_types=1);

namespace TeleBot\System\Core\Traits {
    class QueueableDatabaseStub {
        public array $insertCalls = [];
        public function insert(string $table, array $data): void
        {
            $this->insertCalls[] = compact('table', 'data');
        }
    }

    class VerifiableRequestStub {
        public array $payload = [];
        public string $uri = '/telegram';
        public string $ip = '127.0.0.1';
        public array $headers = [];

        public function json(): array { return $this->payload; }
        public function uri(): string { return $this->uri; }
        public function ip(): string { return $this->ip; }
        public function header(string $key): ?string { return $this->headers[$key] ?? null; }
    }

    class VerifiableResponseStub {
        public ?int $status = null;
        public function setStatusCode(int $code): self { $this->status = $code; return $this; }
        public function end(): void {}
    }

    $queueableDbStub = new QueueableDatabaseStub();
    $verifiableRequestStub = new VerifiableRequestStub();
    $verifiableResponseStub = new VerifiableResponseStub();

    function database(): QueueableDatabaseStub
    {
        global $queueableDbStub;
        return $queueableDbStub;
    }

    function request(): VerifiableRequestStub
    {
        global $verifiableRequestStub;
        return $verifiableRequestStub;
    }

    function response(): VerifiableResponseStub
    {
        global $verifiableResponseStub;
        return $verifiableResponseStub;
    }
}

namespace Tests\Unit\System\Core {

use PHPUnit\Framework\TestCase;
use TeleBot\System\Core\Enums\LogType;
use TeleBot\System\Core\Traits\Expirable;
use TeleBot\System\Core\Traits\Loggable;
use TeleBot\System\Core\Traits\Queueable;
use TeleBot\System\Core\Traits\Verifiable;

class CoreTraitsTest extends TestCase
{
    public function testExpirableTraitHasExpiredAndRestore(): void
    {
        $instance = new class {
            use Expirable;
            public function hasExpiredPublic(?array $data): bool { return $this->hasExpired($data); }
            public function restorePublic(mixed $data): mixed { return $this->restore($data); }
        };

        $this->assertTrue($instance->hasExpiredPublic(['ttl' => time() - 1]));
        $this->assertFalse($instance->hasExpiredPublic(['ttl' => time() + 600]));
        $this->assertSame('value', $instance->restorePublic(['ttl' => time(), 'content' => 'value']));
        $this->assertSame('raw', $instance->restorePublic('raw'));
    }

    public function testLoggableTraitWritesLogFile(): void
    {
        $dir = sys_get_temp_dir() . '/loggable-test-' . uniqid();
        @mkdir($dir, 0777, true);

        $logger = new class($dir) {
            use Loggable;
            public static string $dir;
            public const LOG_DIR = '';
            public function __construct(string $dir)
            {
                self::$dir = $dir;
            }

            protected static function writeToFile(LogType $logType, string $content): void
            {
                $filename = self::$dir . '/' . $logType->value . date('_Y_m_d') . '.log';
                file_put_contents($filename, $content . PHP_EOL, FILE_APPEND);
            }
        };

        $logger::info('hello');
        $expected = $dir . '/info' . date('_Y_m_d') . '.log';

        $this->assertFileExists($expected);
        $this->assertStringContainsString('hello', (string)file_get_contents($expected));
    }

    public function testQueueableTraitDispatchUsesDatabaseHelper(): void
    {
        global $queueableDbStub;
        $queueableDbStub->insertCalls = [];

        $job = new class {
            use Queueable;
        };

        $job::dispatch(['id' => 1]);

        $this->assertCount(1, $queueableDbStub->insertCalls);
        $this->assertSame('queue_jobs', $queueableDbStub->insertCalls[0]['table']);
    }

    public function testVerifiableTraitVerifyUserIdHonorsWhiteAndBlackLists(): void
    {
        global $verifiableRequestStub;

        $verifiableRequestStub->payload = [
            'update_id' => 1,
            'message' => ['from' => ['id' => '42']],
        ];

        $subject = new class {
            use Verifiable;
            public static array $config = [
                'users' => ['whitelist' => ['42'], 'blacklist' => []],
                'routes' => [],
            ];
            public function verifyUserIdPublic(): bool
            {
                return $this->verifyUserId();
            }
        };

        $this->assertTrue($subject->verifyUserIdPublic());

        $subject::$config['users'] = ['whitelist' => [], 'blacklist' => ['42']];
        $this->assertFalse($subject->verifyUserIdPublic());
    }
}
}
