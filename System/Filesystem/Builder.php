<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Filesystem;

use TeleBot\System\Filesystem\Enums\BuildType;

class Builder
{

    /** @var array $lines lines to write to the output file */
    protected array $lines = [];

    /**
     * default constructor
     *
     * @param string $key
     * @param BuildType $buildType
     */
    public function __construct(
        protected string    $key,
        protected BuildType $buildType
    ) {}

    /**
     * generate builder
     *
     * @param string $name
     * @param BuildType $buildType
     * @return Builder
     */
    public static function build(string $name, BuildType $buildType = BuildType::HANDLER): Builder
    {
        return new Builder($name, $buildType);
    }

    /**
     * open builder
     *
     * @return $this
     */
    public function open(): self
    {
        $this->lines[] = '<?php';
        $this->lines[] = '';

        return $this;
    }

    /**
     * write line content
     *
     * @param string $content
     * @param int $tab
     * @return $this
     */
    public function line(string $content, int $tab = 0): self
    {
        if ($tab > 0) {
            $content = str_repeat("\t", $tab) . $content;
        }

        $this->lines[] = $content;

        return $this;
    }

    /**
     * save class
     *
     * @return bool
     */
    public function save(): bool
    {
        $directory = match ($this->buildType) {
            BuildType::JOB => 'App/Jobs/',
            BuildType::SCRIPT => 'App/Scripts/',
            BuildType::HANDLER => 'App/Handlers/',
        };

        $filename = $directory . ucwords($this->key) . '.php';
        if (file_exists($filename)) {
            die('[!] file already exists.');
        }

        $this->lines[] = ''; // adds a new empty line to the end of the file
        return file_put_contents($filename, join(PHP_EOL, $this->lines));
    }

}