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

class IncomingMessage
{

    /** @var int $id message id */
    public int $id;

    /** @var DateTime $date message date */
    public DateTime $date;

    /** @var string|null $messageThreadId supergroup thread id */
    public ?string $messageThreadId;

    /** @var string|null $businessConnectionId id of the business connection */
    public ?string $businessConnectionId = null;

    /** @var DateTime|null $editDate Date the message was last edited in Unix time */
    public ?DateTime $editDate = null;

    /** @var bool|null $hasProtectedContent True, if the message can't be forwarded */
    public ?bool $hasProtectedContent = null;

    /** @var bool|null $isFromOnline True,
     * if the message was sent by an implicit action, for example,
     * as an away or a greeting business message, or as a scheduled message
     */
    public ?bool $isFromOnline = null;

    /** @var string|null $mediaGroupId
     * The unique identifier of a media message group this message belongs to
     */
    public ?string $mediaGroupId = null;

    /** @var string|null $authorSignature
     * Signature of the post author for messages in channels,
     * or the custom title of an anonymous group administrator
     */
    public ?string $authorSignature = null;

    /** @var string|null $connectedWebsite
     * The domain name of the website on which the user has logged in
     */
    public ?string $connectedWebsite = null;

    /** @var User|null $from message sender */
    public ?User $from = null;

    /** @var User|null $viaBot Bot through which the message was sent */
    public ?User $viaBot = null;

    /** @var Chat|null $chat conversation details */
    public ?Chat $chat = null;

    /** @var Chat|null $senderChat sender of the message */
    public ?Chat $senderChat = null;

    /** @var Chat|null $senderBusinessBot
     * The bot that actually sent the message on behalf of the business account
     */
    public ?Chat $senderBusinessBot = null;

    /** @var User[]|null $newChatMembers
     * New members that were added to the group or supergroup and information about them
     * (the bot itself may be one of these members)
     */
    public ?array $newChatMembers = null;

    /** @var User|null $leftChatMember
     * A member was removed from the group, information about them (this member may be the bot itself)
     */
    public ?User $leftChatMember = null;

    /** @var UsersShared|null $usersShared users were shared with the bot */
    public ?UsersShared $usersShared = null;

    /** @var ChatShared|null $chatShared a chat was shared with the bot */
    public ?ChatShared $chatShared = null;

    /** @var WriteAccessAllowed|null $writeAccessAllowed
     * the user allowed the bot to write messages after adding it to the attachment or side menu
     */
    public ?WriteAccessAllowed $writeAccessAllowed = null;

    /** @var PassportData|null $passportData Telegram Passport data */
    public ?PassportData $passportData = null;

    /** @var IncomingProximityAlertTriggered|null $proximityAlertTriggered
     * A user in the chat triggered another user's proximity alert while sharing Live Location.
     */
    public ?IncomingProximityAlertTriggered $proximityAlertTriggered = null;

    /** @var IncomingChatBoostAdded|null $boostAdded user boosted the chat */
    public ?IncomingChatBoostAdded $boostAdded = null;

    /** @var ChatBackground|null $chatBackgroundSet chat background set */
    public ?ChatBackground $chatBackgroundSet = null;

    /** @var bool|null $isTopicMessage True, if the message is sent to a forum topic */
    public ?bool $isTopicMessage = null;

    /** @var bool|null $isAutomaticForward True, if the message is a channel post that was automatically forwarded to the connected discussion group */
    public ?bool $isAutomaticForward = null;

    /** @var bool|null $showCaptionAboveMedia True, if the caption must be shown above the message media */
    public ?bool $showCaptionAboveMedia = null;

    /** @var bool|null $hasMediaSpoiler True, if the message media is covered by a spoiler animation */
    public ?bool $hasMediaSpoiler = null;

    /** @var bool|null $deleteChatPhoto the chat photo was deleted */
    public ?bool $deleteChatPhoto = null;

    /** @var bool|null $groupChatCreated the group has been created */
    public ?bool $groupChatCreated = null;

    /** @var bool|null $supergroupChatCreated the supergroup has been created */
    public ?bool $supergroupChatCreated = null;

    /** @var bool|null $channelChatCreated the channel has been created */
    public ?bool $channelChatCreated = null;

    /** @var string|null $newChatTitle A chat title was changed to this value */
    public ?string $newChatTitle = null;

    /** @var PhotoSize[]|null $newChatPhoto A chat photo was change to this value */
    public ?array $newChatPhoto = null;

    /** @var string|null $effectId Unique identifier of the message effect added to the message */
    public ?string $effectId = null;

    /** @var string|null $migrateToChatId The group has been migrated to a supergroup with the specified identifier */
    public ?string $migrateToChatId = null;

    /** @var string|null $migrateFromChatId The supergroup has been migrated from a group with the specified identifier */
    public ?string $migrateFromChatId = null;

    /** @var MessageEntity[]|null $entities message entities */
    public ?array $entities = null;

    /** @var string|null $caption caption */
    public ?string $caption = null;

    /** @var MessageEntity[]|null $captionEntities list of caption entities */
    public ?array $captionEntities = null;

    /** @var MessageAutoDeleteTimerChanged|null $messageAutoDeleteTimerChanged auto-delete timer settings changed in the chat */
    public ?MessageAutoDeleteTimerChanged $messageAutoDeleteTimerChanged = null;

    /** @var MaybeInaccessibleMessage|null $pinnedMessage Specified message was pinned */
    public ?MaybeInaccessibleMessage $pinnedMessage = null;

    /** @var LinkPreviewOptions|null $linkPreviewOptions
     * Options used for link preview generation for the message,
     * if it is a text message and link preview options were changed
     */
    public ?LinkPreviewOptions $linkPreviewOptions = null;

    /** @var InlineKeyboard|null $replyMarkup inline keyboard */
    public ?InlineKeyboard $replyMarkup = null;

    /** @var IncomingMessage|null $replyToMessage For replies in the same chat and message thread, the original message */
    public ?IncomingMessage $replyToMessage = null;

    /** @var IncomingStory|null $replyToStory For replies to a story, the original story */
    public ?IncomingStory $replyToStory = null;

    /** @var ExternalReplyInfo|null $externalReply Information about the message that is being replied to, which may come from another chat or forum topic */
    public ?ExternalReplyInfo $externalReply = null;

    /** @var MessageOrigin|null $forwardOrigin Information about the original message for forwarded messages */
    public ?MessageOrigin $forwardOrigin = null;

    /** @var Forward|null $forward message source */
    public ?Forward $forward = null;

    /** @var int|null $senderBoostCount the number of boosts added by the user */
    public ?int $senderBoostCount = null;

    /** @var TextQuote|null $textQuote For replies that quote part of the original message, the quoted part of the message */
    public ?TextQuote $quote = null;

    /** @var string|null message text */
    public ?string $text = null;

    /** @var IncomingPhoto|null $photo photo message */
    public ?IncomingPhoto $photo = null;

    /** @var IncomingVideo|null $video video message */
    public ?IncomingVideo $video = null;

    /** @var IncomingVideoNote|null $videoNote video note message */
    public ?IncomingVideoNote $videoNote = null;

    /** @var IncomingAudio|null $audio audio message */
    public ?IncomingAudio $audio = null;

    /** @var IncomingVoice|null $voice voice note message */
    public ?IncomingVoice $voice = null;

    /** @var IncomingDocument|null $document document message */
    public ?IncomingDocument $document = null;

    /** @var IncomingContact|null $contact contact message */
    public ?IncomingContact $contact = null;

    /** @var IncomingAnimation|null $animation animation message */
    public ?IncomingAnimation $animation = null;

    /** @var IncomingLocation|null $location location message */
    public ?IncomingLocation $location = null;

    /** @var IncomingDice|null $dice dice message */
    public ?IncomingDice $dice = null;

    /** @var IncomingSticker|null $sticker sticker message */
    public ?IncomingSticker $sticker = null;

    /** @var IncomingStory|null $story story message */
    public ?IncomingStory $story = null;

    /** @var IncomingGame|null $game game message */
    public ?IncomingGame $game = null;

    /** @var IncomingPoll|null $poll poll message */
    public ?IncomingPoll $poll = null;

    /** @var IncomingVenue|null $venue venue message */
    public ?IncomingVenue $venue = null;

    /** @var IncomingInvoice|null $invoice invoice message */
    public ?IncomingInvoice $invoice = null;

    /** @var IncomingSuccessfulPayment|null $successfulPayment */
    public ?IncomingSuccessfulPayment $successfulPayment = null;

    /** @var IncomingForumTopicCreated|null $forumTopicCreated forum topic created */
    public ?IncomingForumTopicCreated $forumTopicCreated = null;

    /** @var IncomingForumTopicEdited|null $forumTopicEdited forum topic edited */
    public ?IncomingForumTopicEdited $forumTopicEdited = null;

    /** @var IncomingForumTopicClosed|null $forumTopicClosed forum topic closed */
    public ?IncomingForumTopicClosed $forumTopicClosed = null;

    /** @var IncomingForumTopicReopened|null $forumTopicReopened forum topic reopened */
    public ?IncomingForumTopicReopened $forumTopicReopened = null;

    /** @var IncomingGeneralForumTopicHidden|null $generalForumTopicHidden the 'General' forum topic hidden */
    public ?IncomingGeneralForumTopicHidden $generalForumTopicHidden = null;

    /** @var IncomingGeneralForumTopicUnhidden|null $generalForumTopicUnhidden the 'General' forum topic unhidden */
    public ?IncomingGeneralForumTopicUnhidden $generalForumTopicUnhidden = null;

    /** @var IncomingGiveawayCreated|null $giveawayCreated a scheduled giveaway was created */
    public ?IncomingGiveawayCreated $giveawayCreated = null;

    /** @var Giveaway|null $giveaway The message is a scheduled giveaway message */
    public ?Giveaway $giveaway = null;

    /** @var GiveawayWinners|null $giveawayWinners A giveaway with public winners was completed */
    public ?GiveawayWinners $giveawayWinners = null;

    /** @var IncomingGiveawayCompleted|null $giveawayCompleted a giveaway without public winners was completed */
    public ?IncomingGiveawayCompleted $giveawayCompleted = null;

    /** @var IncomingVideoChatScheduled|null $videoChatScheduled video chat scheduled */
    public ?IncomingVideoChatScheduled $videoChatScheduled = null;

    /** @var IncomingVideoChatStarted|null $videoChatStarted video chat started */
    public ?IncomingVideoChatStarted $videoChatStarted = null;

    /** @var IncomingVideoChatEnded|null $videoChatEnded video chat ended */
    public ?IncomingVideoChatEnded $videoChatEnded = null;

    /** @var VideoChatParticipantsInvited|null $videoChatParticipantsInvited new participants invited to a video chat */
    public ?VideoChatParticipantsInvited $videoChatParticipantsInvited = null;

    /** @var IncomingWebAppData|null $webAppData data sent by a Web App */
    public ?IncomingWebAppData $webAppData = null;

    /**
     * default constructor
     *
     * @param array $message
     */
    public function __construct(protected readonly array $message)
    {
        try {
            $this->id = (int)$this->message['message_id'];
            $this->date = new DateTime(date('Y-m-d H:i:s T', $this->message['date']));
            $this->messageThreadId = $this->message['message_thread_id'] ?? null;
            $this->businessConnectionId = $this->message['business_connection_id'] ?? null;
            $this->hasProtectedContent = $this->message['has_protected_content'] ?? null;
            $this->isFromOnline = $this->message['is_from_online'] ?? null;
            $this->authorSignature = $this->message['author_signature'] ?? null;
            $this->connectedWebsite = $this->message['connected_website'] ?? null;
            $this->mediaGroupId = $this->message['media_group_id'] ?? null;
            $this->isTopicMessage = $this->message['is_topic_message'] ?? null;
            $this->isAutomaticForward = $this->message['is_automatic_forward'] ?? null;
            $this->showCaptionAboveMedia = $this->message['show_caption_above_media'] ?? null;
            $this->hasMediaSpoiler = $this->message['has_media_spoiler'] ?? null;
            $this->deleteChatPhoto = $this->message['delete_chat_photo'] ?? null;
            $this->groupChatCreated = $this->message['group_chat_created'] ?? null;
            $this->supergroupChatCreated = $this->message['supergroup_chat_created'] ?? null;
            $this->channelChatCreated = $this->message['channel_chat_created'] ?? null;
            $this->newChatTitle = $this->message['new_chat_title'] ?? null;
            $this->effectId = $this->message['effect_id'] ?? null;
            $this->migrateToChatId = $this->message['migrate_to_chat_id'] ?? null;
            $this->migrateFromChatId = $this->message['migrate_from_chat_id'] ?? null;
            $this->senderBoostCount = $this->message['sender_boost_count'] ?? null;

            $this->text = $this->message['text'] ?? null;
            if (array_key_exists('edit_date', $this->message)) {
                $this->editDate = new DateTime(date('Y-m-d H:i:s T', $this->message['edit_date']));
            }

            if (array_key_exists('link_preview_options', $this->message)) {
                $this->linkPreviewOptions = new LinkPreviewOptions($this->message['link_preview_options']);
            }

            if (array_key_exists('text', $this->message)) {
                $this->entities = array_map(
                    fn($e) => new MessageEntity($this->text, $e),
                    ($this->message['entities'] ?? [])
                );
            }

            $this->caption = $this->message['caption'] ?? null;
            if (array_key_exists('caption', $this->message)) {
                $this->captionEntities = array_map(
                    fn($e) => new MessageEntity($this->caption, $e),
                    ($this->message['caption_entities'] ?? [])
                );
            }

            /** <Chat> */
            if (array_key_exists('chat', $this->message)) {
                $this->chat = new Chat($this->message['chat']);
            }

            /** <From> */
            if (array_key_exists('from', $this->message)) {
                $this->from = new User($this->message['from']);
            }

            /** <NewChatPhoto> */
            if (array_key_exists('new_chat_photo', $this->message)) {
                $this->newChatPhoto = array_map(
                    fn($photo) => new PhotoSize($photo),
                    $this->message['new_chat_photo']
                );
            }

            /** <ViaBot> */
            if (array_key_exists('via_bot', $this->message)) {
                $this->viaBot = new User($this->message['via_bot']);
            }

            /** <SenderChat> */
            if (array_key_exists('sender_chat', $this->message)) {
                $this->senderChat = new Chat($this->message['sender_chat']);
            }

            /** <SenderBusinessBot> */
            if (array_key_exists('sender_business_bot', $this->message)) {
                $this->senderBusinessBot = new Chat($this->message['sender_business_bot']);
            }

            /** <NewChatMembers> */
            if (array_key_exists('new_chat_members', $this->message)) {
                $this->newChatMembers = array_map(
                    fn($user) => new User($user),
                    $this->message['new_chat_members']
                );
            }

            /** <LeftChatMember> */
            if (array_key_exists('left_chat_member', $this->message)) {
                $this->leftChatMember = new User($this->message['left_chat_member']);
            }

            /** <ReplyToMessage> */
            if (array_key_exists('reply_to_message', $this->message)) {
                $this->replyToMessage = new IncomingMessage($this->message['reply_to_message']);
            }

            /** <ReplyToStory> */
            if (array_key_exists('reply_to_story', $this->message)) {
                $this->replyToStory = new IncomingStory($this->message['reply_to_story']);
            }

            /** <FrowardFrom|ForwardFromChat> */
            if (array_intersect(['forward_from', 'forward_from_chat'], array_keys($this->message))) {
                $this->forward = new Forward($this->message);
            }

            /** <Photo> */
            if (array_key_exists('photo', $this->message)) {
                $this->photo = new IncomingPhoto($this->message['photo']);
            }

            /** <Video> */
            if (array_key_exists('video', $this->message)) {
                $this->video = new IncomingVideo($this->message['video']);
            }

            /** <VideoNote> */
            if (array_key_exists('video_note', $this->message)) {
                $this->videoNote = new IncomingVideoNote($this->message['video_note']);
            }

            /** <Voice> */
            if (array_key_exists('voice', $this->message)) {
                $this->voice = new IncomingVoice($this->message['voice']);
            }

            /** <Audio> */
            if (array_key_exists('audio', $this->message)) {
                $this->audio = new IncomingAudio($this->message['audio']);
            }

            /** <Document> */
            if (array_key_exists('document', $this->message)) {
                $this->document = new IncomingDocument($this->message['document']);
            }

            /** <Contact> */
            if (array_key_exists('contact', $this->message)) {
                $this->contact = new IncomingContact($this->message['contact']);
            }

            /** <Dice> */
            if (array_key_exists('dice', $this->message)) {
                $this->dice = new IncomingDice($this->message['dice']);
            }

            /** <Animation> */
            if (array_key_exists('animation', $this->message)) {
                $this->animation = new IncomingAnimation($this->message['animation']);
            }

            /** <Sticker> */
            if (array_key_exists('sticker', $this->message)) {
                $this->sticker = new IncomingSticker($this->message['sticker']);
            }

            /** <Location> */
            if (array_key_exists('location', $this->message)) {
                $this->location = new IncomingLocation($this->message['location']);
            }

            /** <Venue> */
            if (array_key_exists('venue', $this->message)) {
                $this->venue = new IncomingVenue($this->message['venue']);
            }

            /** <Invoice> */
            if (array_key_exists('invoice', $this->message)) {
                $this->invoice = new IncomingInvoice($this->message['invoice']);
            }

            /** <Story> */
            if (array_key_exists('story', $this->message)) {
                $this->story = new IncomingStory($this->message['story']);
            }

            /** <Game> */
            if (array_key_exists('game', $this->message)) {
                $this->game = new IncomingGame($this->message['game']);
            }

            /** <Poll> */
            if (array_key_exists('poll', $this->message)) {
                $this->poll = new IncomingPoll($this->message['poll']);
            }

            /** <SuccessfulPayment> */
            if (array_key_exists('successful_payment', $this->message)) {
                $this->successfulPayment = new IncomingSuccessfulPayment($this->message['successful_payment']);
            }

            /** <VideoChatScheduled> */
            if (array_key_exists('video_chat_scheduled', $this->message)) {
                $this->videoChatScheduled = new IncomingVideoChatScheduled($this->message['video_chat_scheduled']);
            }

            /** <IncomingVideoChatParticipantsInvited> */
            if (array_key_exists('video_chat_participants_invited', $this->message)) {
                $this->videoChatParticipantsInvited = new VideoChatParticipantsInvited(
                    $this->message['video_chat_participants_invited']
                );
            }

            /** <VideoChatStarted> */
            if (array_key_exists('video_chat_started', $this->message)) {
                $this->videoChatStarted = new IncomingVideoChatStarted($this->message['video_chat_started']);
            }

            /** <VideoChatEnded> */
            if (array_key_exists('video_chat_ended', $this->message)) {
                $this->videoChatEnded = new IncomingVideoChatEnded($this->message['video_chat_ended']);
            }

            /** <ForumTopicCreated> */
            if (array_key_exists('forum_topic_created', $this->message)) {
                $this->forumTopicCreated = new IncomingForumTopicCreated($this->message['forum_topic_created']);
            }

            /** <ForumTopicEdited> */
            if (array_key_exists('forum_topic_edited', $this->message)) {
                $this->forumTopicEdited = new IncomingForumTopicEdited($this->message['forum_topic_edited']);
            }

            /** <ForumTopicClosed> */
            if (array_key_exists('forum_topic_closed', $this->message)) {
                $this->forumTopicClosed = new IncomingForumTopicClosed($this->message['forum_topic_closed']);
            }

            /** <ForumTopicReopened> */
            if (array_key_exists('forum_topic_reopened', $this->message)) {
                $this->forumTopicReopened = new IncomingForumTopicReopened($this->message['forum_topic_reopened']);
            }

            /** <GeneralForumTopicHidden> */
            if (array_key_exists('general_forum_topic_hidden', $this->message)) {
                $this->generalForumTopicHidden = new IncomingGeneralForumTopicHidden(
                    $this->message['general_forum_topic_hidden']
                );
            }

            /** <GeneralForumTopicUnhidden> */
            if (array_key_exists('general_forum_topic_unhidden', $this->message)) {
                $this->generalForumTopicUnhidden = new IncomingGeneralForumTopicUnhidden(
                    $this->message['general_forum_topic_unhidden']
                );
            }

            /** <Giveaway> */
            if (array_key_exists('giveaway', $this->message)) {
                $this->giveaway = new Giveaway($this->message['giveaway']);
            }

            /** <GiveawayWinners> */
            if (array_key_exists('giveaway_winners', $this->message)) {
                $this->giveawayWinners = new GiveawayWinners($this->message['giveaway_winners']);
            }

            /** <GiveawayCreated> */
            if (array_key_exists('giveaway_created', $this->message)) {
                $this->giveawayCreated = new IncomingGiveawayCreated($this->message['giveaway_created']);
            }

            /** <GiveawayCompleted> */
            if (array_key_exists('giveaway_completed', $this->message)) {
                $this->giveawayCompleted = new IncomingGiveawayCompleted($this->message['giveaway_completed']);
            }

            /** <WebAppData> */
            if (array_key_exists('web_app_data', $this->message)) {
                $this->webAppData = new IncomingWebAppData($this->message['web_app_data']);
            }

            /** <ProximityAlertTriggered> */
            if (array_key_exists('proximity_alert_triggered', $this->message)) {
                $this->proximityAlertTriggered = new IncomingProximityAlertTriggered(
                    $this->message['proximity_alert_triggered']
                );
            }

            /** <ChatBoostAdded> */
            if (array_key_exists('boost_added', $this->message)) {
                $this->boostAdded = new IncomingChatBoostAdded($this->message['boost_added']);
            }

            /** <ChatBackground> */
            if (array_key_exists('chat_background_set', $this->message)) {
                $this->chatBackgroundSet = new ChatBackground($this->message['chat_background_set']);
            }

            /** <WriteAccessAllowed> */
            if (array_key_exists('write_access_allowed', $this->message)) {
                $this->writeAccessAllowed = new WriteAccessAllowed($this->message['write_access_allowed']);
            }

            /** <UsersShared> */
            if (array_key_exists('users_shared', $this->message)) {
                $this->usersShared = new UsersShared($this->message['users_shared']);
            }

            /** <ChatShared> */
            if (array_key_exists('chat_shared', $this->message)) {
                $this->chatShared = new ChatShared($this->message['chat_shared']);
            }

        } catch (\Exception $ex) {}
    }
}