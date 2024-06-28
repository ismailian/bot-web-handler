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
| Command                         | Description                 |
|---------------------------------|-----------------------------|
| `php cli update:check`          | check for available updates |
| `php cli update:apply`          | apply available updates     |
| `php cli handler:make <name>`   | create new handler          |
| `php cli handler:delete <name>` | delete a handler            |

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
    echo '[+] File ID: ' . $photo->photos[0]->fileId;
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
    echo '[+] File ID: ' . $video->fileId;
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
    echo '[+] response: ' . $query->data['game:type'];
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
public function text(IncomingMessage $message): void
{
    echo '[+] user sent: ' . $message->text;
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
public function text(IncomingMessage $message): void
{
    echo '[+] user sent: ' . $message->text;
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
    Session::set('input', 'age');
    $this->telegram->sendMessage('Please type in your age:');
}

/**
 * handle incoming user input
 * 
 * @return void
 */
#[Awaits('input', 'age')]
#[Text(Validator: new NumberValidator())]
public function setAge(IncomingMessage $message): void
{
    $age = $message->text;
    Session::unset('input');
}
```

#### Handling Payments (in 3 steps)
1. Create invoice and send it to users
2. Answer pre-checkout query by confirming `the product` is available
3. Process successful payments

```php
/**
 * handle incoming purchase command
 *
 * @return void
 */
#[Command('purchase')]
public function invoice(): void
{
    $this->telegram->sendInvoice(
        title: 'Product title',
        description: 'Product description',
        payload: 'data for your internal processing',
        prices: [
            ['label' => 'Product Name', 'amount' => 100]
        ],
        currency: 'USD',
        providerToken: 'Token assigned to you after linking your stripe account with telegram'
    );
}

/**
 * handle incoming pre checkout query
 *
 * @param IncomingPreCheckoutQuery $preCheckoutQuery
 * @return void
 */
#[PreCheckoutQuery]
public function checkout(IncomingPreCheckoutQuery $preCheckoutQuery): void
{
    $this->telegram->answerPreCheckoutQuery(
        queryId: $preCheckoutQuery->id,
        ok: true, // true if ok, otherwise false
        errorMessage: 'if you have any errors'
    );
}

/**
 * handle incoming successful payment
 *
 * @return void
 */
#[SuccessfulPayment]
public function paid(IncomingSuccessfulPayment $successfulPayment): void
{
    // save payment info and send thank you message
}
```

## Simple Queue
Currently, the queue only uses database to manage jobs, in the future, other methods will be integrated.

#### setup queue in 3 steps:
1. run migration: `php cli queue:init`
2. run queue worker: `php cli queue:work`
3. create job class:
   *typically, you would create a `Jobs` directory in the `App` folder where your jobs will live. Job classes must implement the `IJob` interface.*

#### dispatching jobs:
```php
/**
 * handle incoming urls
 *
 * @return void
 */
#[Url]
public function urls(IncomingUrl $url): void
{
    QueueWorker::dispatch(UrlParserJob::class, [
        'url' => $url
    ]);
    
    $this->telegram->sendMessage('Your url is being processed!');
}
```