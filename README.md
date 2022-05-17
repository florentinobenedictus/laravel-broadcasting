# Laravel Broadcasting dengan Pusher
## Daftar Isi
- [Public Channel](#public-channel)<br>
- [Private & Presence Channel](https://github.com/florentinobenedictus/laravel-broadcasting/blob/master/README.md)<br>

## Public Channel
Pada Public Channel, tiap user dapat terhubung dan melakukan broadcasting pada channel tanpa perlu melakukan autentikasi

### Langkah Pembuatan Public Channel

#### 1. Buat Project Baru
`composer create-project laravel/laravel laravel-pusher`

#### 2. Buat Account Pada [pusher.com](https://pusher.com/)
![](https://i.snipboard.io/5ztOQx.jpg)

#### 3. Create App Pada Channel Pusher
![](https://i.snipboard.io/Hd23iB.jpg)<br>
Pilih cluster terdekat dengan lokasi sekarang (ap1)<br>
Front End: Default (Choose an option)<br>
Back End: Laravel<br>

#### 4. Edit `.env` Project
![](https://i.snipboard.io/1QdYNB.jpg)<br>
- Buka App Key pada channel<br>
![](https://i.snipboard.io/zMiVFT.jpg)<br>
- Ubah `BROADCAST_DRIVER=log` menjadi `BROADCAST_DRIVER=pusher`
- Masukkan data App Key pada channel pada `PUSHER_APP_`

#### 5. Edit `config/app.php` Project
Uncomment `App\Providers\BroadcastServiceProvider::class`

#### 6. Install Composer Pusher Pada Project
`composer require pusher/pusher-php-server`

#### 7. Buat event pada project
- `php artisan make:event Chat`
- Tambahkan `implements Should Broadcast` pada class Chat
- Tambahkan variabel untuk menyimpan username dan pesan
```php
public $username = '';
public $message = '';
```
- Isi fungsi construct dengan variabel
```php
public function __construct($username, $message){
                $this->username = $username;
		$this->message = $message;
}
```
- Ubah isi broadcastOn() sehingga mereturn public channel
```php
public function broadcastOn()
{
		return new Channel('chatRoom');
}
```
- Tambahkan fungsi broadcastAs()
```php
public function broadcastAs(){
		return 'chatting';
}
```
Hasil akhir `app/Events/Chat.php` akan menjadi seperti ini
```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Chat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	public $username = '';
	public $message = '';
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($username, $message)
    {
        $this->username = $username;
		$this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
		return new Channel('chatRoom');
    }
	
	public function broadcastAs(){
		return 'chatting';
	}
}
```

#### 8. Buat View Baru `room.blade.php` yang berisi form chat dan button send
Kemudian tambahkan [bootstrap](https://www.bootstrapcdn.com/) pada head
```blade.php
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
```
Hasil akhir `room.blade.php` akan terlihat seperti ini
```blade.php
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Chat Room</title>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/app.css">
</head>
<body>
	<center><h1>Chat Room</h1></center>
	<div class="comntainer m-8">
		<div class="row m-8 p-5">
			<div class="col-xs-6">
				<div class="card">
					<div class="card-body">
						<form id="chatForm">
							<div class="mb-3" id="messageOutput"></div>
							<hr>
							<div class="form-group mb-3">
								<input type="text" class="form-control" id="message" placeholder="Message">
							</div>
							<button type="submit" class="btn btn-success">Send</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="./js/app.js"></script>
</body>
</html>
```
#### 9. buka `resource/js/bootstrap.js`
Kemudian uncomment bagian bawah<br>
![](https://i.snipboard.io/dHy6xF.jpg)

#### 10. Setup JS
- Install [node.js](https://nodejs.org/en/)
- `npm install` pada project
- Install pusher-js `npm install pusher-js` dan laravel-echo `npm install laravel-echo`
- Buat `resource/js/chat.js`
- Buat variabel yang menyimpan ID pada `room.blade.php`
```js
const messageElement = document.getElementById('messageOutput');
const userMessageInput = document.getElementById('message');
const sendMessageForm = document.getElementById('chatForm');
```
- Simpan URL yang akan digunakan untuk mendapat variabel username
```js
let url = window.location;
let urlNew = new URL(url);
let userName = urlNew.searchParams.get('name');
```
- Buat listener button submit
```js
sendMessageForm.addEventListener('submit', function(e){
	e.preventDefault();
	
	if(userMessageInput.value != ''){
		axios({
			method: 'post',
			url: '/sendMessage',
			data:{
				username: userName,
				message: userMessageInput.value
			}
		})
	}
	window.Echo.channel('chatRoom').listen('.chatting', (res) => {
		messageElement.innerHTML += '<div><strong>'+ res.username +': </strong><span>'+ res.message +'</span></div>';
        sendMessageForm.addEventListener('submit');
	});
	
	userMessageInput.value = '';
});
```
Dalam membuat listener akan menggunakan metode axios, lalu juga perlu ditambahkan listener chatting `window.Echo.channel` yang merupakan formatting public channel. Agar pesan dapat ditampilkan maka tiap pesan juga akan dikirim ke `room.blade.php`
```js
window.Echo.channel('chatRoom').listen('.chatting', (res) => {
		messageElement.innerHTML += '<div><strong>'+ res.username +': </strong><span>'+ res.message +'</span></div>';
        sendMessageForm.addEventListener('submit');
	});
```
Hasil akhir `chat.js` akan menjadi seperti ini
```js
const messageElement = document.getElementById('messageOutput');
const userMessageInput = document.getElementById('message');
const sendMessageForm = document.getElementById('chatForm');

let url = window.location;
let urlNew = new URL(url);
let userName = urlNew.searchParams.get('name');

sendMessageForm.addEventListener('submit', function(e){
	e.preventDefault();
	
	if(userMessageInput.value != ''){
		axios({
			method: 'post',
			url: '/sendMessage',
			data:{
				username: userName,
				message: userMessageInput.value
			}
		})
	}
	window.Echo.channel('chatRoom').listen('.chatting', (res) => {
		messageElement.innerHTML += '<div><strong>'+ res.username +': </strong><span>'+ res.message +'</span></div>';
        sendMessageForm.addEventListener('submit');
	});
	
	userMessageInput.value = '';
});
```
- Terakhir, tambahkan `chat.js` pada `app.js` dengan
```js
require('./chat');
```

#### 11. Compile JS
- Hasil compile akan berada pada folder public
- Gunakan `npm run dev` atau `npm run watch` jika ingin JS auto compile tiap terjadi perubahan
- 
#### 12. Buat controller
- `php artisan make:controller MessageController`
- Buat fungsi index yang akan membuat event dengan value username dan message
```php
<?php

namespace App\Http\Controllers;

use App\Events\Chat;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request){
		event(new Chat(
			$request->input('username'),
			$request->input('message'),
		));
		
		return true;
	}
}
```
#### 13. Edit `routes.web.php`
- Tambahkan `use App\Http\Controllers\MessagesController;`
- Ubah route `/` sehingga mengarah ke `room.blade.php`
```php
Route::get('/', function () {
    return view('room');
});
```
- Tambahkan route Post
```php
Route::post('/sendMessage', [MessagesController::class, 'index']);
```
Hasil akhir akan menjadi seperti ini
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
    return view('room');
});

Route::post('/sendMessage', [MessagesController::class, 'index']);
```

#### 14. Cara menggunakan
- `php artisan serve`
- Buka `127.0.0.1:8000` diikuti `?name=<nama user>` yang akan digunakan menyimpan variabel nama user
- Buka tab baru untuk menambah jumlah user
- Kirim pesan dengan mengisi message lalu menekan submit
![](https://i.snipboard.io/fZ4Rva.jpg)
- Untuk melihat event yang ada, kita dapat menggunakan debug console pada pusher.com
![](https://i.snipboard.io/soe7Yb.jpg)
