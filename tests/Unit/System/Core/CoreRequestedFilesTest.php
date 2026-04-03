<?php

declare(strict_types=1);

namespace Tests\Unit\System\Core;

use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use TeleBot\System\Core\Bootstrap;
use TeleBot\System\Core\Database;
use TeleBot\System\Core\Dotenv;
use TeleBot\System\Core\Filesystem;
use TeleBot\System\Core\Handler;
use TeleBot\System\Core\HelperLoader;
use TeleBot\System\Core\Logger;
use TeleBot\System\Core\Process;
use TeleBot\System\Core\Router;
use TeleBot\System\Core\Runtime;
use TeleBot\System\Core\ServiceContainer;
use TeleBot\System\Core\Enums\RuntimeType;

require_once __DIR__ . '/../../../../System/Utils/Helpers.php';
require_once __DIR__ . '/../../../../System/Utils/Instances.php';

class CoreRequestedFilesTest extends TestCase
{
    public function testBootstrapInitLoadsConfig(): void
    {
        Bootstrap::init();

        $this->assertIsArray(Bootstrap::$config);
        $this->assertArrayHasKey('routes', Bootstrap::$config);
    }

    public function testDatabaseThrowsWhenRequiredEnvMissing(): void
    {
        putenv('DATABASE_NAME=');
        putenv('DATABASE_USER=');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Database name not defined');
        new Database();
    }

    public function testDotenvLoadsKeyValuePairsFromCustomFile(): void
    {
        $tmp = sys_get_temp_dir() . '/dotenv-test-' . uniqid() . '.env';
        file_put_contents($tmp, "UNIT_DOTENV_KEY=loaded\n");

        $prop = new ReflectionProperty(Dotenv::class, 'envFilename');
        $prop->setAccessible(true);
        $original = $prop->getValue();
        $prop->setValue($tmp);

        try {
            Dotenv::load();
            $this->assertSame('loaded', getenv('UNIT_DOTENV_KEY'));
        } finally {
            $prop->setValue($original);
            @unlink($tmp);
            putenv('UNIT_DOTENV_KEY=');
        }
    }

    public function testFilesystemHelpersResolveFilesAndNamespaces(): void
    {
        $root = sys_get_temp_dir() . '/fs-test-' . uniqid();
        @mkdir($root . '/A', 0777, true);
        file_put_contents($root . '/A/Foo.php', '<?php');

        $files = Filesystem::getFiles($root);
        $this->assertContains($root . '/A/Foo', $files);

        $namespaced = Filesystem::getNamespacedFiles($root, 'Tmp');
        $this->assertContains('\\Tmp\\' . $root . '\\A\\Foo', $namespaced);

        $handlerPath = Filesystem::getNamespacedFile('Foo::handle', $root);
        $this->assertNotNull($handlerPath);
    }

    public function testHelperLoaderLoadSupportsSingleAndList(): void
    {
        $file1 = sys_get_temp_dir() . '/helper-a-' . uniqid() . '.php';
        $file2 = sys_get_temp_dir() . '/helper-b-' . uniqid() . '.php';
        file_put_contents($file1, '<?php return ["a" => 1];');
        file_put_contents($file2, '<?php return ["b" => 2];');

        try {
            $single = HelperLoader::load($file1, false);
            $multi = HelperLoader::load([$file1, $file2], false);

            $this->assertSame(['a' => 1], $single);
            $this->assertCount(2, $multi);
        } finally {
            @unlink($file1);
            @unlink($file2);
        }
    }

    public function testLoggerGetInstanceReturnsLogger(): void
    {
        $this->assertInstanceOf(Logger::class, Logger::getInstance());
    }

    public function testProcessRunExecutesCommand(): void
    {
        $this->assertSame('core-test', Process::run('printf', 'core-test'));
    }

    public function testQueueAndHandlerAndRouterAndRuntimeAndContainerBasics(): void
    {
        $this->assertTrue(class_exists(\TeleBot\System\Core\Queue::class));

        $handler = new class {
            public array $calls = [];
            public function hello(string $name): void { $this->calls[] = $name; }
        };
        Handler::assign($handler, 'hello', ['world']);
        Handler::run();
        $this->assertSame(['world'], $handler->calls);

        $routerRef = new ReflectionClass(Router::class);
        $method = $routerRef->getMethod('getFullRoute');
        $method->setAccessible(true);
        $router = new Router();
        $this->assertSame('GET /v1/users', $method->invoke($router, '/users', '/v1', 'GET'));

        Runtime::setType(RuntimeType::REQUEST);
        $this->assertSame(RuntimeType::REQUEST, Runtime::getType());
        $this->assertTrue(Runtime::is(RuntimeType::REQUEST));
        $this->assertInstanceOf(Runtime::class, Runtime::getInstance());

        $container = new ServiceContainer();
        $container['svc'] = \ArrayObject::class;
        $this->assertTrue(isset($container['svc']));
        $this->assertInstanceOf(\ArrayObject::class, $container['svc']);
        unset($container['svc']);
        $this->assertFalse(isset($container['svc']));
    }
}
