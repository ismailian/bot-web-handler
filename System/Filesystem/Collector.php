<?php

namespace TeleBot\System\Filesystem;

class Collector
{

    /** @var string default project namespace */
    const DEFAULT_NS = 'TeleBot';

    /**
     * recursively collect files in path based on their extension
     *
     * @param string $folder
     * @param string $extension
     * @return array
     */
    public static function getFiles(string $folder, string $extension = 'php'): array
    {
        $pattern = $folder . '/*.' . $extension;
        $files = glob($pattern);

        if ($folders = glob("$folder/*", GLOB_ONLYDIR)) {
            foreach ($folders as $subFolder) {
                $files = [...$files, ...self::getFiles($subFolder)];
            }
        }

        return array_map(fn($f) => preg_replace('/\.\S+$/', '', $f), $files);
    }

    /**
     * recursively collect namespaces files in path based on their extension
     *
     * @param string $folder
     * @param string $namespace
     * @param string $extension
     * @return array
     */
    public static function getNamespacedFiles(string $folder, string $namespace = self::DEFAULT_NS, string $extension = 'php'): array
    {
        $pattern = $folder . '/*.' . $extension;
        $files = glob($pattern);

        if ($folders = glob("$folder/*", GLOB_ONLYDIR)) {
            foreach ($folders as $subFolder) {
                $files = [...$files, ...self::getFiles($subFolder)];
            }
        }

        return array_map(
            fn($f) => "\\$namespace\\" . preg_replace(['/\.\S+$/', '/\//'], ['', '\\'], $f),
            $files
        );
    }

    /**
     * get full file path
     *
     * @param string $handlerName
     * @param string $folder
     * @return string|null
     */
    public static function getNamespacedFile(string $handlerName, string $folder = 'App/Handlers'): ?string
    {
        $handlers = array_values(array_filter(
            self::getNamespacedFiles($folder),
            fn($h) => str_ends_with($h, explode('::', $handlerName)[0])
        ));

        return empty($handlers) ? null : array_values($handlers)[0];
    }

}