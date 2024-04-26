<?php

namespace TeleBot\System;

class Cli
{

    protected static string $payload = "PD9waHAKCm5hbWVzcGFjZSBUZWxlQm90XEFwcFxIYW5kbGVyc1x7e2hhbmRsZXJQYXRofX07Cgp1c2UgVGVsZUJvdFxTeXN0ZW1cQmFzZUV2ZW50OwoKY2xhc3Mge3toYW5kbGVyTmFtZX19IGV4dGVuZHMgQmFzZUV2ZW50IHt9";

    /**
     * update system
     *
     * @return bool
     */
    public static function update(): bool
    {
        return true;
    }

    /**
     * create new handler
     *
     * @param array $args
     * @return void
     */
    public static function makeHandler(array $args): void
    {
        try {
            $segments = explode('/', $args['name']);
            $fullPath = join(DIRECTORY_SEPARATOR, ['App', 'Handlers', ...$segments]);
            if (count($segments) > 1) {
                @mkdir(dirname($fullPath), recursive: true);
            }

            $fileName = basename($fullPath);
            if (str_ends_with($fullPath, '.php')) {
                $fullPath = str_replace('.php', '', $fullPath);
                $fileName = basename($fullPath);
            }

            $payload = base64_decode(self::$payload);
            $payload = str_replace('{{handlerPath}}', dirname(join('\\', $segments)), $payload);
            $payload = str_replace('{{handlerName}}', $fileName, $payload);

            $handler = fopen("$fullPath.php", 'w');
            fwrite($handler, $payload);
            fclose($handler);

            echo "[+] handler created successfully!" . PHP_EOL;
            return;

        } catch (\Exception) {}
        echo "[-] failed to create handler!" . PHP_EOL;
    }

    /**
     * delete existing handler
     *
     * @param array $args
     * @return void
     */
    public static function deleteHandler(array $args): void
    {
        $segments = explode('/', $args['name']);
        $fullPath = join(DIRECTORY_SEPARATOR, ['App', 'Handlers', ...$segments]);
        if (!str_ends_with($fullPath, '.php')) {
            $fullPath .= '.php';
        }

        if (@unlink($fullPath)) {
            echo "[+] handler deleted successfully!" . PHP_EOL;
        } else {
            echo "[-] failed to delete handler!" . PHP_EOL;
        }
    }

}