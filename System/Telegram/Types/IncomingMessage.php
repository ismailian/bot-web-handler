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
use TeleBot\System\Telegram\Support\Hydrator;
use TeleBot\System\Telegram\Support\CanReplyWith;
use TeleBot\System\Telegram\Traits\{MapProp, CanForward, CanDelete};

class IncomingMessage
{

    use CanDelete, CanForward;

    /** @var int $id message id */
    #[MapProp('message_id')]
    public int $id;

    /** @var DateTime $date message date */
    #[MapProp('date', asDateTime: true)]
    public DateTime $date;

    /** @var string|null $messageThreadId supergroup thread id */
    #[MapProp('message_thread_id')]
    public ?string $messageThreadId;

    /** @var string|null $businessConnectionId id of the business connection */
    #[MapProp('business_connection_id')]
    public ?string $businessConnectionId = null;

    /** @var DateTime|null $editDate Date the message was last edited in Unix time */
    #[MapProp('edit_date', asDateTime: true)]
    public ?DateTime $editDate = null;

    /** @var bool|null $hasProtectedContent True, if the message can't be forwarded */
    #[MapProp('has_protected_content')]
    public ?bool $hasProtectedContent = null;

    /** @var bool|null $isFromOnline True,
     * if the message was sent by an implicit action, for example,
     * as an away or a greeting business message, or as a scheduled message
     */
    #[MapProp('is_from_online')]
    public ?bool $isFromOnline = null;

    /** @var string|null $mediaGroupId
     * The unique identifier of a media message group this message belongs to
     */
    #[MapProp('media_group_id')]
    public ?string $mediaGroupId = null;

    /** @var string|null $authorSignature
     * Signature of the post author for messages in channels,
     * or the custom title of an anonymous group administrator
     */
    #[MapProp('author_signature')]
    public ?string $authorSignature = null;

    /** @var string|null $connectedWebsite
     * The domain name of the website on which the user has logged in
     */
    #[MapProp('connected_website')]
    public ?string $connectedWebsite = null;

    /** @var User|null $from message sender */
    #[MapProp('from', User::class)]
    public ?User $from = null;

    /** @var User|null $viaBot Bot through which the message was sent */
    #[MapProp('via_bot', User::class)]
    public ?User $viaBot = null;

    /** @var Chat|null $chat conversation details */
    #[MapProp('chat', Chat::class)]
    public ?Chat $chat = null;

    /** @var Chat|null $senderChat sender of the message */
    #[MapProp('sender_chat', Chat::class)]
    public ?Chat $senderChat = null;

    /**
     * @var Chat|null $senderBusinessBot The bot that actually sent the message on behalf of the business account
     */
    #[MapProp('sender_business_bot', Chat::class)]
    public ?Chat $senderBusinessBot = null;

    /** @var User[]|null $newChatMembers
     * New members that were added to the group or supergroup and information about them
     * (the bot itself may be one of these members)
     */
    #[MapProp('new_chat_members', User::class, isArray: true)]
    public ?array $newChatMembers = null;

    /** @var User|null $leftChatMember
     * A member was removed from the group, information about them (this member may be the bot itself)
     */
    #[MapProp('left_chat_member', User::class)]
    public ?User $leftChatMember = null;

    /** @var UsersShared|null $usersShared users were shared with the bot */
    #[MapProp('users_shared', UsersShared::class)]
    public ?UsersShared $usersShared = null;

    /** @var ChatShared|null $chatShared a chat was shared with the bot */
    #[MapProp('chat_shared', ChatShared::class)]
    public ?ChatShared $chatShared = null;

    /** @var Forward|null $forwardFrom user that the chat was forwarded from */
    #[MapProp('forward_from:', Forward::class)]
    public ?Forward $forwardFrom = null;

    /** @var Forward|null $forwardFromChat chat that the message was forwarded from */
    #[MapProp('forward_from_chat:', Forward::class)]
    public ?Forward $forwardFromChat = null;

    /** @var WriteAccessAllowed|null $writeAccessAllowed
     * the user allowed the bot to write messages after adding it to the attachment or side menu
     */
    #[MapProp('write_access_allowed', WriteAccessAllowed::class)]
    public ?WriteAccessAllowed $writeAccessAllowed = null;

    /** @var PassportData|null $passportData Telegram Passport data */
    #[MapProp('passport_data', PassportData::class)]
    public ?PassportData $passportData = null;

    /** @var IncomingProximityAlertTriggered|null $proximityAlertTriggered
     * A user in the chat triggered another user's proximity alert while sharing Live Location.
     */
    #[MapProp('proximity_alert_triggered', IncomingProximityAlertTriggered::class)]
    public ?IncomingProximityAlertTriggered $proximityAlertTriggered = null;

    /** @var IncomingChatBoostAdded|null $boostAdded user boosted the chat */
    #[MapProp('boost_added', IncomingChatBoostAdded::class)]
    public ?IncomingChatBoostAdded $boostAdded = null;

    /** @var ChatBackground|null $chatBackgroundSet chat background set */
    #[MapProp('chat_background_set', ChatBackground::class)]
    public ?ChatBackground $chatBackgroundSet = null;

    /** @var bool|null $isTopicMessage True, if the message is sent to a forum topic */
    #[MapProp('is_topic_message')]
    public ?bool $isTopicMessage = null;

    /** @var bool|null $isAutomaticForward True, if the message is a channel post that was automatically forwarded to the connected discussion group */
    #[MapProp('is_automatic_forward')]
    public ?bool $isAutomaticForward = null;

    /** @var bool|null $showCaptionAboveMedia True, if the caption must be shown above the message media */
    #[MapProp('show_caption_above_media')]
    public ?bool $showCaptionAboveMedia = null;

    /** @var bool|null $hasMediaSpoiler True, if the message media is covered by a spoiler animation */
    #[MapProp('has_media_spoiler')]
    public ?bool $hasMediaSpoiler = null;

    /** @var bool|null $deleteChatPhoto the chat photo was deleted */
    #[MapProp('delete_chat_photo')]
    public ?bool $deleteChatPhoto = null;

    /** @var bool|null $groupChatCreated the group has been created */
    #[MapProp('group_chat_created')]
    public ?bool $groupChatCreated = null;

    /** @var bool|null $supergroupChatCreated the supergroup has been created */
    #[MapProp('supergroup_chat_created')]
    public ?bool $supergroupChatCreated = null;

    /** @var bool|null $channelChatCreated the channel has been created */
    #[MapProp('channel_chat_created')]
    public ?bool $channelChatCreated = null;

    /** @var string|null $newChatTitle A chat title was changed to this value */
    #[MapProp('new_chat_title')]
    public ?string $newChatTitle = null;

    /** @var PhotoSize[]|null $newChatPhoto A chat photo was change to this value */
    #[MapProp('new_chat_photo', PhotoSize::class, isArray: true)]
    public ?array $newChatPhoto = null;

    /** @var string|null $effectId Unique identifier of the message effect added to the message */
    #[MapProp('effect_id')]
    public ?string $effectId = null;

    /** @var string|null $migrateToChatId The group has been migrated to a supergroup with the specified identifier */
    #[MapProp('migrate_to_chat_id')]
    public ?string $migrateToChatId = null;

    /** @var string|null $migrateFromChatId The supergroup has been migrated from a group with the specified identifier */
    #[MapProp('migrate_from_chat_id')]
    public ?string $migrateFromChatId = null;

    /** @var MessageEntities|null $entities message entities */
    #[MapProp('entities:', MessageEntities::class)]
    public ?MessageEntities $entities = null;

    /** @var string|null $caption caption */
    #[MapProp('caption')]
    public ?string $caption = null;

    /** @var MessageEntities|null $captionEntities list of caption entities */
    #[MapProp('caption_entities:', MessageEntities::class)]
    public ?MessageEntities $captionEntities = null;

    /** @var MessageAutoDeleteTimerChanged|null $messageAutoDeleteTimerChanged auto-delete timer settings changed in the chat */
    #[MapProp('message_auto_delete_timer_changed', MessageAutoDeleteTimerChanged::class)]
    public ?MessageAutoDeleteTimerChanged $messageAutoDeleteTimerChanged = null;

    /** @var MaybeInaccessibleMessage|null $pinnedMessage Specified message was pinned */
    #[MapProp('pinned_message', MaybeInaccessibleMessage::class)]
    public ?MaybeInaccessibleMessage $pinnedMessage = null;

    /** @var LinkPreviewOptions|null $linkPreviewOptions
     * Options used for link preview generation for the message,
     * if it is a text message and link preview options were changed
     */
    #[MapProp('link_preview_options', LinkPreviewOptions::class)]
    public ?LinkPreviewOptions $linkPreviewOptions = null;

    /** @var InlineKeyboard|null $replyMarkup inline keyboard */
    #[MapProp('reply_markup', InlineKeyboard::class)]
    public ?InlineKeyboard $replyMarkup = null;

    /** @var IncomingMessage|null $replyToMessage For replies in the same chat and message thread, the original message */
    #[MapProp('reply_to_message', IncomingMessage::class)]
    public ?IncomingMessage $replyToMessage = null;

    /** @var IncomingStory|null $replyToStory For replies to a story, the original story */
    #[MapProp('reply_to_story', IncomingStory::class)]
    public ?IncomingStory $replyToStory = null;

    /** @var ExternalReplyInfo|null $externalReply Information about the message that is being replied to, which may come from another chat or forum topic */
    #[MapProp('external_reply', ExternalReplyInfo::class)]
    public ?ExternalReplyInfo $externalReply = null;

    /** @var MessageOrigin|null $forwardOrigin Information about the original message for forwarded messages */
    #[MapProp('forward_origin', MessageOrigin::class)]
    public ?MessageOrigin $forwardOrigin = null;

    /** @var int|null $senderBoostCount the number of boosts added by the user */
    #[MapProp('sender_boost_count')]
    public ?int $senderBoostCount = null;

    /** @var TextQuote|null $textQuote For replies that quote part of the original message, the quoted part of the message */
    #[MapProp('quote', TextQuote::class)]
    public ?TextQuote $quote = null;

    /** @var string|null message text */
    #[MapProp('text')]
    public ?string $text = null;

    /** @var IncomingPhoto|null $photo photo message */
    #[MapProp('photo', IncomingPhoto::class)]
    public ?IncomingPhoto $photo = null;

    /** @var IncomingVideo|null $video video message */
    #[MapProp('video', IncomingVideo::class)]
    public ?IncomingVideo $video = null;

    /** @var IncomingVideoNote|null $videoNote video note message */
    #[MapProp('video_note', IncomingVideoNote::class)]
    public ?IncomingVideoNote $videoNote = null;

    /** @var IncomingAudio|null $audio audio message */
    #[MapProp('audio', IncomingAudio::class)]
    public ?IncomingAudio $audio = null;

    /** @var IncomingVoice|null $voice voice note message */
    #[MapProp('voice', IncomingVoice::class)]
    public ?IncomingVoice $voice = null;

    /** @var IncomingDocument|null $document document message */
    #[MapProp('document', IncomingDocument::class)]
    public ?IncomingDocument $document = null;

    /** @var IncomingContact|null $contact contact message */
    #[MapProp('contact', IncomingContact::class)]
    public ?IncomingContact $contact = null;

    /** @var IncomingAnimation|null $animation animation message */
    #[MapProp('animation', IncomingAnimation::class)]
    public ?IncomingAnimation $animation = null;

    /** @var IncomingLocation|null $location location message */
    #[MapProp('location', IncomingLocation::class)]
    public ?IncomingLocation $location = null;

    /** @var IncomingDice|null $dice dice message */
    #[MapProp('dice', IncomingDice::class)]
    public ?IncomingDice $dice = null;

    /** @var IncomingSticker|null $sticker sticker message */
    #[MapProp('sticker', IncomingSticker::class)]
    public ?IncomingSticker $sticker = null;

    /** @var IncomingStory|null $story story message */
    #[MapProp('story', IncomingStory::class)]
    public ?IncomingStory $story = null;

    /** @var IncomingGame|null $game game message */
    #[MapProp('game', IncomingGame::class)]
    public ?IncomingGame $game = null;

    /** @var IncomingPoll|null $poll poll message */
    #[MapProp('poll', IncomingPoll::class)]
    public ?IncomingPoll $poll = null;

    /** @var IncomingVenue|null $venue venue message */
    #[MapProp('venue', IncomingVenue::class)]
    public ?IncomingVenue $venue = null;

    /** @var IncomingInvoice|null $invoice invoice message */
    #[MapProp('invoice', IncomingInvoice::class)]
    public ?IncomingInvoice $invoice = null;

    /** @var IncomingSuccessfulPayment|null $successfulPayment */
    #[MapProp('successful_payment', IncomingSuccessfulPayment::class)]
    public ?IncomingSuccessfulPayment $successfulPayment = null;

    /** @var IncomingForumTopicCreated|null $forumTopicCreated forum topic created */
    #[MapProp('forum_topic_created', IncomingForumTopicCreated::class)]
    public ?IncomingForumTopicCreated $forumTopicCreated = null;

    /** @var IncomingForumTopicEdited|null $forumTopicEdited forum topic edited */
    #[MapProp('forum_topic_edited', IncomingForumTopicEdited::class)]
    public ?IncomingForumTopicEdited $forumTopicEdited = null;

    /** @var IncomingForumTopicClosed|null $forumTopicClosed forum topic closed */
    #[MapProp('forum_topic_closed', IncomingForumTopicClosed::class)]
    public ?IncomingForumTopicClosed $forumTopicClosed = null;

    /** @var IncomingForumTopicReopened|null $forumTopicReopened forum topic reopened */
    #[MapProp('forum_topic_reopened', IncomingForumTopicReopened::class)]
    public ?IncomingForumTopicReopened $forumTopicReopened = null;

    /** @var IncomingGeneralForumTopicHidden|null $generalForumTopicHidden the 'General' forum topic hidden */
    #[MapProp('general_forum_topic_hidden', IncomingGeneralForumTopicHidden::class)]
    public ?IncomingGeneralForumTopicHidden $generalForumTopicHidden = null;

    /** @var IncomingGeneralForumTopicUnhidden|null $generalForumTopicUnhidden the 'General' forum topic unhidden */
    #[MapProp('general_forum_topic_unhidden', IncomingGeneralForumTopicUnhidden::class)]
    public ?IncomingGeneralForumTopicUnhidden $generalForumTopicUnhidden = null;

    /** @var IncomingGiveawayCreated|null $giveawayCreated a scheduled giveaway was created */
    #[MapProp('giveaway_created', IncomingGiveawayCreated::class)]
    public ?IncomingGiveawayCreated $giveawayCreated = null;

    /** @var Giveaway|null $giveaway The message is a scheduled giveaway message */
    #[MapProp('giveaway', Giveaway::class)]
    public ?Giveaway $giveaway = null;

    /** @var GiveawayWinners|null $giveawayWinners A giveaway with public winners was completed */
    #[MapProp('giveaway_winners', GiveawayWinners::class)]
    public ?GiveawayWinners $giveawayWinners = null;

    /** @var IncomingGiveawayCompleted|null $giveawayCompleted a giveaway without public winners was completed */
    #[MapProp('giveaway_completed', IncomingGiveawayCompleted::class)]
    public ?IncomingGiveawayCompleted $giveawayCompleted = null;

    /** @var IncomingVideoChatScheduled|null $videoChatScheduled video chat scheduled */
    #[MapProp('video_chat_scheduled', IncomingVideoChatScheduled::class)]
    public ?IncomingVideoChatScheduled $videoChatScheduled = null;

    /** @var IncomingVideoChatStarted|null $videoChatStarted video chat started */
    #[MapProp('video_chat_started', IncomingVideoChatStarted::class)]
    public ?IncomingVideoChatStarted $videoChatStarted = null;

    /** @var IncomingVideoChatEnded|null $videoChatEnded video chat ended */
    #[MapProp('video_chat_ended', IncomingVideoChatEnded::class)]
    public ?IncomingVideoChatEnded $videoChatEnded = null;

    /** @var VideoChatParticipantsInvited|null $videoChatParticipantsInvited new participants invited to a video chat */
    #[MapProp('video_chat_participants_invited', VideoChatParticipantsInvited::class)]
    public ?VideoChatParticipantsInvited $videoChatParticipantsInvited = null;

    /** @var IncomingWebAppData|null $webAppData data sent by a Web App */
    #[MapProp('web_app_data', IncomingWebAppData::class)]
    public ?IncomingWebAppData $webAppData = null;

    /** @var CanReplyWith|null $reply reply interface */
    private CanReplyWith|null $reply = null;

    /**
     * default constructor
     *
     * @param array $message
     */
    public function __construct(array $message)
    {
        Hydrator::hydrate($this, $message);
    }

    /**
     * Get reply interface
     *
     * @return CanReplyWith
     */
    public function reply(): CanReplyWith
    {
        if (!$this->reply) {
            $this->reply = new CanReplyWith($this->id, $this->chat?->id);
        }

        return $this->reply;
    }
}