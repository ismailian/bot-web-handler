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