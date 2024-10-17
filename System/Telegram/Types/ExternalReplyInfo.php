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

class ExternalReplyInfo
{

    /** @var MessageOrigin $origin Origin of the message replied to by the given message */
    public MessageOrigin $origin;

    /**
     * @var ?Chat $chat Optional.
     * Chat the original message belongs to.
     * Available only if the chat is a supergroup or a channel.
     */
    public ?Chat $chat = null;

    /**
     * @var int|null $messageId Optional.
     * Unique message identifier inside the original chat.
     * Available only if the original chat is a supergroup or a channel.
     */
    public ?int $messageId = null;

    /**
     * @var LinkPreviewOptions|null $linkPreviewOptions Optional.
     * Options used for link preview generation for the original message,
     * if it is a text message
     */
    public ?LinkPreviewOptions $linkPreviewOptions = null;

    /**
     * @var IncomingAnimation|null $animation Optional.
     * Message is an animation, information about the animation
     */
    public ?IncomingAnimation $animation = null;

    /**
     * @var IncomingAudio|null $audio Optional.
     * Message is an audio file, information about the file
     */
    public ?IncomingAudio $audio = null;

    /**
     * @var IncomingDocument|null $document Optional.
     * Message is a general file, information about the file
     */
    public ?IncomingDocument $document = null;

    /**
     * @var PaidMediaInfo|null $paidMedia Optional.
     * Message contains paid media; information about the paid media
     */
    public ?PaidMediaInfo $paidMedia = null;

    /**
     * @var PhotoSize[]|null $photo Optional.
     * Message is a photo, available sizes of the photo
     */
    public ?array $photo = null;

    /**
     * @var IncomingSticker|null Optional.
     * Message is a sticker, information about the sticker
     */
    public ?IncomingSticker $sticker = null;

    /**
     * @var IncomingStory|null $story Optional.
     * Message is a forwarded story
     */
    public ?IncomingStory $story = null;

    /**
     * @var IncomingVideo|null $video Optional.
     * Message is a video, information about the video
     */
    public ?IncomingVideo $video = null;

    /**
     * @var IncomingVideoNote|null $videoNote Optional.
     * Message is a video note, information about the video message
     */
    public ?IncomingVideoNote $videoNote = null;

    /**
     * @var IncomingVoice|null $voice Optional.
     * Message is a voice message, information about the file
     */
    public ?IncomingVoice $voice = null;

    /**
     * @var bool $hasMediaSpoiler Optional.
     * True, if the message media is covered by a spoiler animation
     */
    public bool $hasMediaSpoiler = true;

    /**
     * @var IncomingContact|null $contact Optional.
     * Message is a shared contact, information about the contact
     */
    public ?IncomingContact $contact = null;

    /**
     * @var IncomingDice|null $dice Optional.
     * Message is a dice with random value
     */
    public ?IncomingDice $dice = null;

    /**
     * @var IncomingGame|null $game Optional.
     * Message is a game, information about the game.
     */
    public ?IncomingGame $game = null;

    /**
     * @var Giveaway|null $giveaway Optional.
     * Message is a scheduled giveaway, information about the giveaway
     */
    public ?Giveaway $giveaway = null;

    /**
     * @var GiveawayWinners|null $giveawayWinners Optional.
     * A giveaway with public winners was completed
     */
    public ?GiveawayWinners $giveawayWinners = null;

    /**
     * @var IncomingInvoice|null $invoice Optional.
     * Message is an invoice for a payment, information about the invoice.
     */
    public ?IncomingInvoice $invoice = null;

    /**
     * @var IncomingLocation|null $location Optional.
     * Message is a shared location, information about the location
     */
    public ?IncomingLocation $location = null;

    /**
     * @var IncomingPoll|null $incomingPoll Optional.
     * Message is a native poll, information about the poll
     */
    public ?IncomingPoll $poll = null;

    /**
     * @var IncomingVenue|null $venue Optional.
     * Message is a venue, information about the venue
     */
    public ?IncomingVenue $venue = null;

    /**
     * default constructor
     *
     * @param array $externalReplyInfo
     * @throws Exception
     */
    public function __construct(protected readonly array $externalReplyInfo)
    {
        $this->origin = new MessageOrigin($this->externalReplyInfo['origin']);
        $this->messageId = $this->externalReplyInfo['message_id'] ?? null;
        $this->hasMediaSpoiler = $this->externalReplyInfo['has_media_spoiler'] ?? true;

        if (array_key_exists('chat', $this->externalReplyInfo)) {
            $this->chat = new Chat($this->externalReplyInfo['chat']);
        }

        if (array_key_exists('link_preview_options', $this->externalReplyInfo)) {
            $this->linkPreviewOptions = new LinkPreviewOptions(
                $this->externalReplyInfo['link_preview_options']
            );
        }

        if (array_key_exists('animation', $this->externalReplyInfo)) {
            $this->animation = new IncomingAnimation(
                $this->externalReplyInfo['animation']
            );
        }

        if (array_key_exists('audio', $this->externalReplyInfo)) {
            $this->audio = new IncomingAudio(
                $this->externalReplyInfo['audio']
            );
        }

        if (array_key_exists('document', $this->externalReplyInfo)) {
            $this->document = new IncomingDocument(
                $this->externalReplyInfo['document']
            );
        }

        if (array_key_exists('paid_media', $this->externalReplyInfo)) {
            $this->paidMedia = new PaidMediaInfo(
                $this->externalReplyInfo['paid_media']
            );
        }

        if (array_key_exists('photo', $this->externalReplyInfo)) {
            $this->photo = array_map(
                fn($p) => new PhotoSize($p),
                $this->externalReplyInfo['photo']
            );
        }

        if (array_key_exists('sticker', $this->externalReplyInfo)) {
            $this->sticker = new IncomingSticker(
                $this->externalReplyInfo['sticker']
            );
        }

        if (array_key_exists('story', $this->externalReplyInfo)) {
            $this->story = new IncomingStory(
                $this->externalReplyInfo['story']
            );
        }

        if (array_key_exists('video', $this->externalReplyInfo)) {
            $this->video = new IncomingVideo(
                $this->externalReplyInfo['video']
            );
        }

        if (array_key_exists('video_note', $this->externalReplyInfo)) {
            $this->videoNote = new IncomingVideoNote(
                $this->externalReplyInfo['video_note']
            );
        }

        if (array_key_exists('voice', $this->externalReplyInfo)) {
            $this->voice = new IncomingVoice(
                $this->externalReplyInfo['voice']
            );
        }

        if (array_key_exists('contact', $this->externalReplyInfo)) {
            $this->contact = new IncomingContact(
                $this->externalReplyInfo['contact']
            );
        }

        if (array_key_exists('dice', $this->externalReplyInfo)) {
            $this->dice = new IncomingDice(
                $this->externalReplyInfo['dice']
            );
        }

        if (array_key_exists('game', $this->externalReplyInfo)) {
            $this->game = new IncomingGame(
                $this->externalReplyInfo['game']
            );
        }

        if (array_key_exists('giveaway', $this->externalReplyInfo)) {
            $this->giveaway = new Giveaway(
                $this->externalReplyInfo['giveaway']
            );
        }

        if (array_key_exists('giveaway_winners', $this->externalReplyInfo)) {
            $this->giveawayWinners = new GiveawayWinners(
                $this->externalReplyInfo['giveaway_winners']
            );
        }

        if (array_key_exists('invoice', $this->externalReplyInfo)) {
            $this->invoice = new IncomingInvoice(
                $this->externalReplyInfo['invoice']
            );
        }

        if (array_key_exists('location', $this->externalReplyInfo)) {
            $this->location = new IncomingLocation(
                $this->externalReplyInfo['location']
            );
        }

        if (array_key_exists('poll', $this->externalReplyInfo)) {
            $this->poll = new IncomingPoll(
                $this->externalReplyInfo['poll']
            );
        }

        if (array_key_exists('venue', $this->externalReplyInfo)) {
            $this->venue = new IncomingVenue(
                $this->externalReplyInfo['venue']
            );
        }
    }

}