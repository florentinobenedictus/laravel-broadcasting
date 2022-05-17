# Private dan Presence Channel Menggunakan Laravel dan Pusher
- [Private Channel](#private-channel)<br>
- [Presence Channel](#presence-channel)<br>

## Private Channel

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
![env_config](https://user-images.githubusercontent.com/73771452/168735866-18c05c56-25a8-4f0d-8113-1984e3c9582a.png)

#### Database Migrations

- Be sure to fill in your database details in your `.env` file before running the migrations:

![env_db](https://user-images.githubusercontent.com/73771452/168735890-77a2bd34-c638-41c6-b7ff-a81b47e41317.png)

```bash
php artisan migrate
```

And finally, start the application:

```bash
php artisan serve
```

- and visit [http://localhost:8000/](http://localhost:8000/) to see the application in action.

![serve](https://user-images.githubusercontent.com/73771452/168737697-8f150687-ef0c-4826-81e8-f4becc1877bb.png)

## Built With

* [Pusher](https://pusher.com/) - APIs to enable devs building realtime features
* [Laravel](https://laravel.com) - The PHP Framework For Web Artisans
* [Vue.js](https://vuejs.org) - The Progressive JavaScript Framework


## Presence Channel
Presence Channel memerlukan autentikasi user agar dapat terhubung seperti Private Channel, tetapi Presence Channel memiliki fitur tambahan yaitu dapat menampilkan list user yang sedang terhubung ke channel.<br>
Hal ini sangat bermanfaat karena `Auth::check()` hanya dapat mengecek autentikasi satu user saja. Dengan menggunakan Presence Channel kita tidak perlu membuat variabel tambahan untuk mengecek last activity pada database maupun menggunakan session dan cache karena fitur sudah tersedia.<br>
### Membuat Presence Channel
Karena juga menggunakan autentikasi dan memiliki kemiripan dengan Private Channel, Presence Channel dapat dibuat hanya dengan melakukan beberapa modifikasi pada Private Channel antara lain:
#### 1. Menambahkan Section untuk menampilkan list username terhubung pada view `chat.blade.php`
```php
<div class="col-md-4">
		<center>
			<h3>Username List:</h3><br>
			<div class="mb-4" id="usernameList"></div>
		</center>
	</div>
	<br><br>
```
Sehingga hasil akhir `chat.blade.php` menjadi seperti ini:
```php
@extends('layouts.app')

@section('content')

<div class="container">
	<div class="col-md-4">
		<center>
			<h3>Username List:</h3><br>
			<div class="mb-4" id="usernameList"></div>
		</center>
	</div>
	<br><br>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Chats</div>

                <div class="panel-body">
                    <chat-messages :messages="messages"></chat-messages>
                </div>
                <div class="panel-footer">
                    <chat-form
                        v-on:messagesent="addMessage"
                        :user="{{ Auth::user() }}"
                    ></chat-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```
#### 2. Modifikasi `public/js/app.js`
- Tambahkan `usernameList` yang mendapatkan ID dari `chat.blade.php` dan array `userArr` yang akan menyimpan user yang sekarang sedang terhubung ke channel
```js
const usernameList = document.getElementById('usernameList');

var userArr = [];
```
- Selanjutnya, dengan menggunakan laravel echo kita dapat mengubah private channel yang sebelumnya menggunakan method private menjadi method join yang merupakan indikator presence channel.<br>
##### Private Channel
```js
Echo.private('chat').listen('MessageSent', function (e) {
            _this.messages.push({
                message: e.message.message,
                user: e.user
            });
        });
```
##### Presence Channel
```js
Echo.join('chat')
			.here((users) => {
				usernameList.innerHTML = "";
				console.log(users);
				userArr = [];
				for(var i = 0; i < users.length; i++)userArr.push(users[i].name);
				for(var i = 0; i < userArr.length; i++)usernameList.innerHTML += '<div><strong>'+ userArr[i] +'</strong><span>'+'</span></div>';
				console.log("here user..");
			})
			.joining((user) => {
				userArr.push(user);
				console.log("joining user..");
			})
			.leaving((user) => {
				userArr = userArr.filter(user);
				console.log("leaving user..");
			})
			.listen('MessageSent', function (e) {
				_this.messages.push({
					message: e.message.message,
					user: e.user
				});
			});
```
Terlihat bahwa selain method listen, Presence Channel sudah memiliki fungsi bawaan yaitu here yang digunakan mendapat seluruh user terhubung, joining yang mengecek user yang baru terhubung ke channel, dan leaving yang mengecek ketika ada user yang disconnect dari channel.<br>
Berikut merupakan tampilan pada `app.js` setelah dilakukan modifikasi
![](https://i.snipboard.io/8two3O.jpg)
#### 3. Ubah `app/Events/MessageSent.php`
- Sebelumnya, fungsi `broadcastOn` masih akan mereturn private channel, oleh karena itu kita dapat mengubahnya menjadi presence channel dengan mengganti
```php
return new PrivateChannel('chat');
```
Menjadi
```php
return new PresenceChannel('chat');
```
Hasil akhir `MessageSent.php` akan terlihat seperti ini
```php
<?php

namespace App\Events;

use App\User;
use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message
     *
     * @var User
     */
    public $user;

    /**
     * Message details
     *
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat');
    }
}
```
#### 4. Ubah `routes/channel.php`
Pada Private Channel, broadcast channel akan mereturn hasil autentikasi
```php
return Auth::check();
```
Pada presence channel juga akan melakukan autentikasi, tetapi jika autentikasi berhasil ang akan direturn adalah array data yang mengandung informasi user
```php
if( Auth::check()){
	return ['name' => $user->name];
   }
```
Sehingga hasil akhir `channel.php` akan terlihat seperti ini
```php
<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat', function ($user) {
	if( Auth::check()){
	return ['name' => $user->name];
   }
});
```
Berikut merupakan tampilan Presence Channel yang telah dibuat
![](https://i.snipboard.io/8Orv5d.jpg)
