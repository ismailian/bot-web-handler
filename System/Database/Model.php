<?php

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
