# Lurkchat bot admin panel template

This template allows you to start your bot without creating admin panel and base functionality every time.

Just code your bot logic

### Get started

#### Clone repo
```
git clone https://github.com/lurkchat/bot-laravel-template.git
```

#### Install composer dependencies
```
composer install
```

#### Install and compile node modules
```
npm i && npm run dev
```

#### Copy .env.example to .env file, and configure env variables
```
cp .env.example .env
```

#### Generate new app key
```
php artisan key:generate
```

#### Cache config
```
php artisan config:cache
```

#### Migrate database
```
php artisan migrate
```

#### Create admin user
```
php artisan make:user
```

#### If you have set LURKCHAT_BOT_TOKEN in .env,run command to set webhook for bot
Note: webhook handler is defined in api.php routes
```
php artisan webhook:set
```

#### Delete .git folder to init yours in th future
```
sudo rm -r .git
```

#### Done
Code bot's functionality and use ready admin panel
