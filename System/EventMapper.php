<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System;

use TeleBot\System\Core\Filesystem;
use ReflectionClass, ReflectionException;
use TeleBot\System\Core\{Bootstrap, Handler};

class EventMapper
{

    /**
     * initialize handler
     *
     * @throws ReflectionException
     */
    public function init(): bool
    {
        new Bootstrap()->boot();
        $handlers = Filesystem::getNamespacedFiles('App/Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            if ($refClass->isSubclassOf(IncomingEvent::class)) {
                foreach ($refClass->getMethods() as $refMethod) {
                    if (!empty($refMethod->getAttributes())) {
                        if (Handler::check($refClass, $refMethod)) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

}