<?php

namespace TeleBot\App\Handlers;

use Exception;
use TeleBot\System\BaseEvent;
use TeleBot\System\Events\CallbackQuery;
use TeleBot\System\Events\Command;
use TeleBot\System\SessionManager;
use TeleBot\System\Types\IncomingCallbackQuery;
use TeleBot\System\Types\InlineKeyboard;

class DiceGame extends BaseEvent
{

    /**
     * handle game start
     *
     * @return void
     * @throws Exception
     */
    #[Command('play')]
    public function play(): void
    {
        /** 1. start and set default values */
        SessionManager::set([
            'game' => [
                'settings' => [
                    'type' => 'bowling',
                    'rounds' => 3,
                    'throws' => 5,
                ],
                'score' => ['bot' => [], 'user' => []]
            ]
        ], 'started');

        /** 2. ask configuration questions */
        $this->telegram
            ->withOptions([
                'reply_markup' => [
                    'inline_keyboard' => (new InlineKeyboard())
                        ->addButton('Bowling', ['game:type' => 'bowling'], InlineKeyboard::CALLBACK_DATA)
                        ->addButton('Basketball', ['game:type' => 'basketball'], InlineKeyboard::CALLBACK_DATA)
                        ->addButton('Dice', ['game:type' => 'dice'], InlineKeyboard::CALLBACK_DATA)
                        ->toArray()
                ]
            ])
            ->sendMessage('What game would you like to play?');
    }

    /**
     * handle game type choice
     *
     * @param IncomingCallbackQuery $query
     * @return void
     * @throws Exception
     */
    #[CallbackQuery('game:type')]
    public function gameType(IncomingCallbackQuery $query): void
    {
        $gameType = $query('game:type');
        $games = ['bowling', 'basketball', 'dice'];

        if (empty($gameType) || !in_array($gameType, $games)) {
            $this->telegram
                ->withOptions([
                    'reply_markup' => [
                        'inline_keyboard' => (new InlineKeyboard())
                            ->addButton('Bowling', ['game:type' => 'bowling'], InlineKeyboard::CALLBACK_DATA)
                            ->addButton('Basketball', ['game:type' => 'basketball'], InlineKeyboard::CALLBACK_DATA)
                            ->addButton('Dice', ['game:type' => 'dice'], InlineKeyboard::CALLBACK_DATA)
                            ->toArray()
                    ]
                ])
                ->sendMessage('What game would you like to play?');
            return;
        }

        /** 1. set game type */
        SessionManager::set([
            'game' => [
                'settings' => [
                    'type' => $gameType,
                    'rounds' => 3,
                    'throws' => 5,
                ],
                'score' => ['bot' => [], 'user' => []]
            ]
        ]);

        /** 2. get rounds number */
        $this->telegram
            ->withOptions([
                'reply_markup' => [
                    'inline_keyboard' => (new InlineKeyboard())
                        ->addButton('3', ['game:rounds' => 3], InlineKeyboard::CALLBACK_DATA)
                        ->addButton('5', ['game:rounds' => 5], InlineKeyboard::CALLBACK_DATA)
                        ->addButton('10', ['game:rounds' => 10], InlineKeyboard::CALLBACK_DATA)
                        ->toArray()
                ]
            ])
            ->sendMessage('How many rounds would like to play?');
    }

    /**
     * handle game rounds choice
     *
     * @param IncomingCallbackQuery $query
     * @return void
     * @throws Exception
     */
    #[CallbackQuery('game:rounds')]
    public function gameRounds(IncomingCallbackQuery $query): void
    {
        $gameRounds = $query('game:rounds');
        $rounds = [3, 5, 10];

        if (empty($gameRounds) || !in_array($gameRounds, $rounds)) {
            $this->telegram
                ->withOptions([
                    'reply_markup' => [
                        'inline_keyboard' => (new InlineKeyboard())
                            ->addButton('Bowling', ['game:type' => 'bowling'], InlineKeyboard::CALLBACK_DATA)
                            ->addButton('Basketball', ['game:type' => 'basketball'], InlineKeyboard::CALLBACK_DATA)
                            ->addButton('Dice', ['game:type' => 'dice'], InlineKeyboard::CALLBACK_DATA)
                            ->toArray()
                    ]
                ])
                ->sendMessage('How many rounds would you like to play?');
            return;
        }

        /** 1. set game rounds */
        SessionManager::set([
            'game' => [
                'settings' => [
                    'type' => SessionManager::get('game.settings.type'),
                    'rounds' => $gameRounds,
                    'throws' => 5,
                ],
                'score' => ['bot' => [], 'user' => []]
            ]
        ]);

        /** 2. get throws number */
        $this->telegram
            ->withOptions([
                'reply_markup' => [
                    'inline_keyboard' => (new InlineKeyboard())
                        ->addButton('5', ['game:throws' => 5], InlineKeyboard::CALLBACK_DATA)
                        ->addButton('10', ['game:throws' => 10], InlineKeyboard::CALLBACK_DATA)
                        ->addButton('15', ['game:throws' => 15], InlineKeyboard::CALLBACK_DATA)
                        ->toArray()
                ]
            ])
            ->sendMessage('How many throws would you like to play?');
    }

    /**
     * handle game rounds choice
     *
     * @param IncomingCallbackQuery $query
     * @return void
     * @throws Exception
     */
    #[CallbackQuery('game:throws')]
    public function gameThrows(IncomingCallbackQuery $query): void
    {
        $gameThrows = $query('game:throws');
        $throws = [5, 10, 15];

        if (empty($gameThrows) || !in_array($gameThrows, $throws)) {
            $this->telegram
                ->withOptions([
                    'reply_markup' => [
                        'inline_keyboard' => (new InlineKeyboard())
                            ->addButton('Bowling', ['game:throws' => 5], InlineKeyboard::CALLBACK_DATA)
                            ->addButton('Basketball', ['game:throws' => 10], InlineKeyboard::CALLBACK_DATA)
                            ->addButton('Dice', ['game:throws' => 15], InlineKeyboard::CALLBACK_DATA)
                            ->toArray()
                    ]
                ])
                ->editMessage( '','How many throws would you like to play?');
            return;
        }

        /** 1. set game throws */
        SessionManager::set([
            'game' => [
                'settings' => [
                    'type' => SessionManager::get('game.settings.type'),
                    'rounds' => SessionManager::get('game.settings.rounds'),
                    'throws' => $gameThrows,
                ],
                'score' => ['bot' => [], 'user' => []]
            ]
        ]);

        /** 2. start game */
        $this->telegram->sendMessage("We are all set! Let's play a game");
    }

}