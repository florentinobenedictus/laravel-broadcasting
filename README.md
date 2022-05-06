# laravel-broadcasting
 
### catatan
1. buat project baru
composer create-project laravel/laravel laravel-pusher

2. buat account pada pusher.com

3. create app pada channel, cluster ap1
front end: default
back end: laravel

4. buka .env pada project, ubah BROADCAST_DRIVER=pusher dari log
masukkan data app key channel pada PUSHER_APP_...=
lalu buka config/app.php, uncomment broadcastserviceprovider

5. composer require pusher/pusher-php-server

6. php artisan make:event Chat

7. isi event chat sesuai ketentuan (shouldbroadcast, parameter, at)

8. buat view baru room.blade.php
bisa template html vscode
copy dari bootstrapcdn.com
lalu buat form chat dan button send

9. buka resource/js/bootstrap.js, uncomment bagian bawah

10. install node js, npm install, npm install pusher-js, npm install laravel-echo

11. npm run watch (biar auto compile)

12. buat chat.js, require chat.js pada app.js

13. php artisan make:controller MessageController

14. tambahkan routing post

15. tambahkan append pada chat.js
