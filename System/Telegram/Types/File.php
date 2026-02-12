<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

use TeleBot\System\Telegram\Traits\MapProp;
use TeleBot\System\Telegram\Traits\Downloadable;

class File
{

    use Downloadable;

    /** @var string $fileId file id */
    #[MapProp('file_id')]
    public string $fileId;

    /** @var string $fileUniqueId unique file id */
    #[MapProp('file_unique_id')]
    public string $fileUniqueId;

    /** @var float|null $fileSize */
    #[MapProp('file_size')]
    public ?float $fileSize = null;

    /** @var string|null $filePath file path */
    #[MapProp('file_path')]
    public ?string $filePath = null;

    /**
     * convert byte file size to human-readable format
     *
     * @return string|null human-readable file size
     */
    public function toHumanReadable(): ?string
    {
        if (!$this->fileSize) return null;

        $n = 0;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        while ($this->fileSize >= 1024 && $n++ < count($units)) {
            $this->fileSize /= 1024;
        }

        return join(' ', [number_format((float) $this->fileSize, 2), $units[$n]]);
    }
}