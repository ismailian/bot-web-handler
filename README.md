# bot-web-handler

This little project is designed specifically for [Telegram](https://core.telegram.org/bots/api) bots.

**Steps**
1. `git clone https://github.com/ismailian/bot-web-handler`
2. `mv bot-web-handler /var/www/`
3. `cd /var/www/bot-web-handler/`
4. `composer install`
5. `cp .env.sample .env`

**[Configurations]**

#### 1. Set the following properties in the `.env` file
- domain url `APP_DOMAIN`
- bot token `BOT_TOKEN`
- webhook secret `TG_BOT_SIGNATURE` (Optional)
- Telegram source IP `TG_SOURCE_IP` (Optional)

#### 2. Set the following properties in the `config.php` file
- routes - routes to accept requests from (Optional)
- whitelist - list of allowed user ids (Optional)
- blacklist - list of disallowed user ids (Optional)

#### 3. Useful commands
| Command                         | Description                |
|---------------------------------|----------------------------|
| `php cli update:check`          | check for updates          |
| `php cli update:apply`          | apply available updates    |
| `php cli handler:make <name>`   | create new handler         |
| `php cli handler:delete <name>` | delete an existing handler |

## Examples
#### Photos
```php
/**
 * handle all incoming photos
 * 
 * @param IncomingPhoto $photo
 * @return void
 */
#[Photo]
public function photos(IncomingPhoto $photo): void
{
    echo '[+] File ID: ' . $photo->getFileId(0);
}
```

#### Videos
```php
/**
 * handle all incoming videos
 * 
 * @param IncomingVideo $video
 * @return void
 */
#[Video]
public function videos(IncomingVideo $video): void
{
    echo '[+] File ID: ' . $video->getFileId();
}
```

#### Commands
```php
/**
 * handle start command
 *
 * @return void
 */
#[Command('start')]
public function onStart(IncomingCommand $command): void
{
    $this->telegram->sendMessage('welcome!');
}
```

#### Callback Queries
```php
/**
 * handle incoming callback query
 *
 * @param IncomingCallbackQuery $query
 * @return void
 */
#[CallbackQuery('game:type')]
public function callbacks(IncomingCallbackQuery $query): void
{
    echo '[+] response: ' . $query('game:type');
}
```

#### Accept Only Private Chats
```php
/**
 * handle incoming text from private chats
 *
 * @return void
 */
#[Text]
#[Chat(Chat::PRIVATE)]
public function text(): void
{
    echo '[+] user sent: ' . $this->event['message']['text'];
}
```

#### Accept Only From User(s) 
```php
/**
 * handle incoming text from specific user/users
 *
 * @return void
 */
#[Text]
#[Only(userId: '<id>', userIds: [...'<id>'])]
public function text(): void
{
    echo '[+] user sent: ' . $this->event['message']['text'];
}
```

#### Accept User Input (note: this filter requires session to work)
1. we would set the `input` value to `age` in order to be able to intercept it later
2. remove the `input` property from the session once you've used it, otherwise any text message containing a number will be captured by the handler.
3. `new NumberValidator` is used to ensure that the handler only intercepts numeric text messages

```php
/**
 * handle incoming age command
 *
 * @return void
 */
#[Command('age')]
public function age(): void
{
    SessionManager::start()->set(['input' => 'age']);
    
    $this->telegram->sendMessage('Please type in your age:');
}

/**
 * handle incoming user input
 * 
 * @return void
 */
#[Awaits('input', 'age')]
#[Text(Validator: new NumberValidator())]
public function setAge(): void
{
    $age = $this->event['message']['text'];
    
    SessionManager::set(['input' => '']);
}
```