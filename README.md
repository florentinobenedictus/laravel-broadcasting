# laravel-chat

Build a chat app with Laravel, Vue.js and Pusher.

## Getting Started

Clone the project repository by running the command below if you use https, use this instead

```bash
git clone https://github.com/florentinobenedictus/laravel-broadcasting.git
```

After cloning, change branch to master and run:

```bash
composer install
```
1. jika error coba `composer update`<br>
2. jika `composer update` juga error, ada kemungkinan karena beda versi laravel.<br>
3. Solusinya overwrite composer.json & composer.lock dan app/Exceptions/Handler.php dengan file yang sesuai versi laravel yang digunakan user
4. Kemudian lakukan `composer require laravel/ui`, `composer clearcache`, lalu coba `composer install` kembali. Clear cache juga dapat dilakukan dengan `php artisan config:cache`<br>

Duplicate file `.env.example` and rename it `.env`

Then run:

```bash
php artisan key:generate
```

### Prerequisites

#### Setup Pusher

If you don't have one already, create a free Pusher account at [https://pusher.com/signup](https://pusher.com/signup) then login to your dashboard and create an app.

Set the `BROADCAST_DRIVER` in your `.env` file to **pusher**:

```txt
BROADCAST_DRIVER=pusher
```

Then fill in your Pusher app credentials in your `.env` file:

```txt
PUSHER_APP_ID=xxxxxx
PUSHER_APP_KEY=xxxxxxxxxxxxxxxxxxxx
PUSHER_APP_SECRET=xxxxxxxxxxxxxxxxxxxx
PUSHER_APP_CLUSTER=xxx
```

#### Database Migrations

Be sure to fill in your database details in your `.env` file before running the migrations:

```bash
php artisan migrate
```

And finally, start the application:

```bash
php artisan serve
```

and visit [http://localhost:8000/](http://localhost:8000/) to see the application in action.

## Built With

* [Pusher](https://pusher.com/) - APIs to enable devs building realtime features
* [Laravel](https://laravel.com) - The PHP Framework For Web Artisans
* [Vue.js](https://vuejs.org) - The Progressive JavaScript Framework
