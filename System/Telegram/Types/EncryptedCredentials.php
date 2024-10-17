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

readonly class EncryptedCredentials
{

    /**
     * @var string $data Base64-encoded encrypted JSON-serialized data with unique user's payload,
     * data hashes and secrets required for EncryptedPassportElement decryption and authentication
     */
    public string $data;

    /** @var string $hash Base64-encoded data hash for data authentication */
    public string $hash;

    /**
     * @var string $secret Base64-encoded secret, encrypted with the bot's public RSA key,
     * required for data decryption
     */
    public string $secret;

    /**
     * default constructor
     *
     * @param array $encryptedCredentials
     */
    public function __construct(protected array $encryptedCredentials)
    {
        $this->data = $this->encryptedCredentials['data'];
        $this->hash = $this->encryptedCredentials['hash'];
        $this->secret = $this->encryptedCredentials['secret'];
    }

}