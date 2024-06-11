<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Database;

use AllowDynamicProperties;

/**
 * the parent class Model
 */
#[AllowDynamicProperties]
class Model
{
    use Eloquent;
    use Mapper;

    /** @var string $type model class */
    public static string $type = '';

    /** @var string $table table name */
    public static string $table = '';

    /** @var array $attributes model attributes */
    public static array $attributes = [];
}
