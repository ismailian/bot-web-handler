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

class LinkPreviewOptions
{

    /** @var bool|null $isDisabled True, if the link preview is disabled */
    public ?bool $isDisabled = null;

    /** @var string|null $url
     * URL to use for the link preview. If empty, then the first URL found in the message text will be used
     */
    public ?string $url = null;

    /** @var bool|null $preferSmallMedia
     * True, if the media in the link preview is supposed to be shrunk;
     * ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
     */
    public ?bool $preferSmallMedia = null;

    /** @var bool|null $preferLargeMedia
     * True, if the media in the link preview is supposed to be enlarged;
     * ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
     */
    public ?bool $preferLargeMedia = null;

    /** @var bool|null $showAboveText
     * True, if the link preview must be shown above the message text;
     * otherwise, the link preview will be shown below the message text
     */
    public ?bool $showAboveText = null;

    /**
     * default constructor
     *
     * @param array $linkPreviewOptions
     */
    public function __construct(protected array $linkPreviewOptions)
    {
        $this->isDisabled = $this->linkPreviewOptions['is_disabled'] ?? null;
        $this->url = $this->linkPreviewOptions['url'] ?? null;
        $this->preferSmallMedia = $this->linkPreviewOptions['prefer_small_media'] ?? null;
        $this->preferLargeMedia = $this->linkPreviewOptions['prefer_large_media'] ?? null;
        $this->showAboveText = $this->linkPreviewOptions['show_above_text'] ?? null;
    }

}