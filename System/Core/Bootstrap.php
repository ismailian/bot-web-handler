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

use Exception;
use ReflectionException;
use TeleBot\System\IncomingEvent;
use TeleBot\System\IncomingRequest;
use TeleBot\System\Telegram\Types\Event;
use TeleBot\System\Filesystem\Collector;
use TeleBot\System\Core\Traits\Verifiable;
use TeleBot\System\Core\Enums\RuntimeType;

class Bootstrap
{

    use Verifiable;

    /** @var array $config */
    public static array $config;

    /**
     * setup necessary configurations to run the app
     *
     * @return void
     * @throws ReflectionException
     * @throws Exception
     */
    public function boot(): void
    {
        set_exception_handler(fn($e) => Logger::onException($e));
        set_error_handler(fn(...$args) => Logger::onError(...$args));

        self::init();
        runtime()->init(self::$config);

        $this->handleIncomingDeployments();
        $this->handleMaintenanceMode();
        $this->handleIncomingRequests();
        $this->handleIncomingEvents();
    }

    /**
     * load config/helper files
     *
     * @return void
     */
    public static function init(): void
    {
        Dotenv::load();
        [self::$config] = FileLoader::load([
            'config.php',
            'System/Utils/*',
        ]);
    }

    /**
     * handles incoming deployments
     *
     * @return void
     */
    protected function handleIncomingDeployments(): void
    {
        if (router()->matches(self::$config['routes']['git'] ?? [])) {
            if (getenv('GIT_AUTO_DEPLOY', true) === 'true') {
                Deployment::run();
            }
        }
    }

    /**
     * handle maintenance mode
     *
     * @return void
     * @throws Exception
     */
    protected function handleMaintenanceMode(): void
    {
        $mode = getenv('MAINTENANCE_MODE', true);
        if (empty($mode) || $mode !== 'down') {
            return;
        }

        $handler = self::$config['maintenance'];

        /** callable function */
        if (is_callable($handler)) {
            $handler(new Event(request()->json()));
        }

        /** handler class */
        if (is_string($handler) && str_contains($handler, '::')) {
            [$class, $method] = explode('::', $handler);
            $class = Collector::getNamespacedFile($class);
            if (Runtime::is(RuntimeType::TELEGRAM) && is_subclass_of($class, IncomingEvent::class)
                || Runtime::is(RuntimeType::REQUEST) && is_subclass_of($class, IncomingRequest::class)
            ) {
                call_user_func_array([new $class, $method], []);
            }
        }

        response()->end();
    }

    /**
     * handle incoming web requests
     *
     * @return void
     * @throws ReflectionException
     */
    protected function handleIncomingRequests(): void
    {
        if ($route = router()->matches(self::$config['routes']['web'] ?? [])) {
            if ($handler = Collector::getNamespacedFile($route['handler'])) {
                (new Handler())
                    ->setConfig(self::$config)
                    ->assign(new $handler,
                        explode('::', $route['handler'])[1], array_values($route['params'])
                    )->run();
            }

            // end connection with a status based on whether handler is properly executed
            response()->setStatusCode(($handler ? 200 : 404))->end();
        }
    }

    /**
     * verifies telegram events
     *
     * @return void
     */
    protected function handleIncomingEvents(): void
    {
        $this->verifyIP()
            ->verifySignature()
            ->verifyRoute()
            ->verifyPayload();

        if (!$this->verifyUserId()) {
            response()->setStatusCode(401)->end();
        }

        if (!empty(($async = getenv('ASYNC')))) {
            if ($async == 'true') {
                response()->close();
            }
        }
    }

}