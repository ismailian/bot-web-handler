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

class EncryptedPassportElement
{

    /**
     * @var string $type Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”, “phone_number”, “email”.
     */
    public string $type;

    /**
     * @var string|null $data Optional.
     * Base64-encoded encrypted Telegram Passport element data provided by the user; available only for “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and “address” types. Can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public ?string $data = null;

    /**
     * @var string|null $phoneNumber Optional.
     * User's verified phone number; available only for “phone_number” type
     */
    public ?string $phoneNumber = null;

    /**
     * @var string|null $email Optional.
     * User's verified email address; available only for “email” type
     */
    public ?string $email = null;

    /**
     * @var PassportFile[]|null $files Optional.
     * Array of encrypted files with documents provided by the user; available only for “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public ?array $files = null;

    /**
     * @var PassportFile|null $frontSide Optional.
     * Encrypted file with the front side of the document, provided by the user; available only for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public ?PassportFile $frontSide = null;

    /**
     * @var PassportFile|null Optional.
     * Encrypted file with the reverse side of the document, provided by the user; available only for “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public ?PassportFile $reverseSide = null;

    /**
     * @var PassportFile|null $selfie Optional.
     * Encrypted file with the selfie of the user holding a document, provided by the user; available if requested for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public ?PassportFile $selfie = null;

    /**
     * @var PassportFile[]|null $translation Optional.
     * Array of encrypted files with translated versions of documents provided by the user; available if requested for “passport”, “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public ?array $translation = null;

    /**
     * @var string $hash Base64-encoded element hash for using in PassportElementErrorUnspecified
     */
    public string $hash;

    /**
     * default constructor
     *
     * @param array $encryptedPassportElement
     * @throws Exception
     */
    public function __construct(protected readonly array $encryptedPassportElement)
    {
        $this->type = $this->encryptedPassportElement['type'];
        $this->hash = $this->encryptedPassportElement['hash'];
        $this->data = $this->encryptedPassportElement['data'] ?? null;
        $this->email = $this->encryptedPassportElement['email'] ?? null;
        $this->phoneNumber = $this->encryptedPassportElement['phone_number'] ?? null;

        if (array_key_exists('files', $this->encryptedPassportElement)) {
            $this->files = array_map(
                fn($f) => new PassportFile($f),
                $this->encryptedPassportElement['files']
            );
        }

        if (array_key_exists('front_side', $this->encryptedPassportElement)) {
            $this->frontSide = new PassportFile($this->encryptedPassportElement['front_side']);
        }

        if (array_key_exists('reverse_side', $this->encryptedPassportElement)) {
            $this->reverseSide = new PassportFile($this->encryptedPassportElement['reverse_side']);
        }

        if (array_key_exists('selfie', $this->encryptedPassportElement)) {
            $this->selfie = new PassportFile($this->encryptedPassportElement['selfie']);
        }

        if (array_key_exists('translation', $this->encryptedPassportElement)) {
            $this->translation = array_map(
                fn($f) => new PassportFile($f),
                $this->encryptedPassportElement['translation']
            );
        }
    }

}