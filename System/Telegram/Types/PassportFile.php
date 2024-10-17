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

use DateTime;
use Exception;

readonly class PassportFile
{

    /**
     * @var string $fileId Identifier for this file, which can be used to download or reuse the file
     */
    public string $fileId;

    /**
     * @var string $fileUniqueId Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     */
    public string $fileUniqueId;

    /**
     * @var int $fileSize File size in bytes
     */
    public int $fileSize;

    /**
     * @var DateTime $fileDate Unix time when the file was uploaded
     */
    public DateTime $fileDate;

    /**
     * default constructor
     *
     * @param array $passportFile
     * @throws Exception
     */
    public function __construct(protected array $passportFile)
    {
        $this->fileId = $this->passportFile['file_id'];
        $this->fileUniqueId = $this->passportFile['file_unique_id'];
        $this->fileSize = $this->passportFile['file_size'];
        $this->fileDate = new DateTime(
            date('Y-m-d H:i:s', $this->passportFile['file_date'])
        );
    }

}