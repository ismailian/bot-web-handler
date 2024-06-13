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

class WriteAccessAllowed
{

    /** @var bool|null $fromRequest
     * True, if the access was granted after the user accepted an explicit request
     * from a Web App sent by the method requestWriteAccess
     */
    public ?bool $fromRequest = null;

    /** @var string|null $webAppName
     * Name of the Web App, if the access was granted when the Web App was launched from a link
     */
    public ?string $webAppName = null;

    /** @var bool|null $fromAttachmentMenu
     * True, if the access was granted when the bot was added to the attachment or side menu
     */
    public ?bool $fromAttachmentMenu = null;

    /**
     * default constructor
     *
     * @param array $writeAccessAllowed
     */
    public function __construct(protected array $writeAccessAllowed)
    {
        $this->fromRequest = $this->writeAccessAllowed['from_request'] ?? null;
        $this->webAppName = $this->writeAccessAllowed['web_app_name'] ?? null;
        $this->fromAttachmentMenu = $this->writeAccessAllowed['from_attachment_menu'] ?? null;
    }
}