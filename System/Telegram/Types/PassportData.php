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

use Exception;

readonly class PassportData
{

    /**
     * @var EncryptedPassportElement[] $data Array with information about documents
     * and other Telegram Passport elements that were shared with the bot
     */
    public array $data;

    /**
     * @var EncryptedCredentials $credentials Encrypted credentials required to decrypt the data
     */
    public EncryptedCredentials $credentials;

    /**
     * default constructor
     *
     * @param array $passportData
     * @throws Exception
     */
    public function __construct(protected array $passportData)
    {
        $this->credentials = new EncryptedCredentials($this->passportData['credentials']);
        $this->data = array_map(
            fn($e) => new EncryptedPassportElement($e),
            $this->passportData['data']
        );
    }

}