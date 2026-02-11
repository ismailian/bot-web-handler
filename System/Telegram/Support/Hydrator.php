<?php

namespace TeleBot\System\Telegram\Support;

use ReflectionClass;
use ReflectionProperty;
use ReflectionException;
use TeleBot\System\Telegram\Traits\MapProp;

class Hydrator
{

    /**
     * Hydrate object properties
     *
     * @param string|object $objectOrClass object or class name
     * @param array $data data to hydrate the object with
     * @return object
     */
    public static function hydrate(string|object $objectOrClass, array $data): object
    {
        try {
            $reflection = new ReflectionClass($objectOrClass);
            if (is_string($objectOrClass)) {
                $objectOrClass = $reflection->newInstance($data);
            }

            foreach ($reflection->getProperties() as $property) {
                self::hydrateProperty($objectOrClass, $property, $data);
            }

            return $objectOrClass;
        } catch (ReflectionException $e) {
            return new $objectOrClass($data);
        }
    }

    /**
     * map property to its class
     *
     * @param object $object
     * @param ReflectionProperty $property
     * @param array $data
     * @return void
     */
    private static function hydrateProperty(object $object, ReflectionProperty $property, array $data): void
    {
        $attributes = $property->getAttributes(MapProp::class);
        if (empty($attributes)) {
            return;
        }

        /** @var MapProp $map */
        $map = $attributes[0]->newInstance();
        if (!array_key_exists($map->key, $data)) {
            return;
        }

        $value = $map->useSelf ? $data : $data[$map->source];
        if ($map->asDateTime && is_int($value)) {
            $value = new \DateTime()->setTimestamp($value);
        }

        if ($map->isArray && $map->type && is_array($value)) {
            $value = array_map(
                fn($item) => new $map->type($item),
                $value
            );
        } elseif ($map->type && is_array($value)) {
            $value = new $map->type($value);
        }

        $property->setValue($object, $value);
    }
}
