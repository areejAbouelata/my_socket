<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

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

Route::get('/', function () {

    Redis::set('name', 'Taylor');
    $values = Redis::lrange('names', 5, 10);
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/create/post', [App\Http\Controllers\PostController::class, 'create'])->name('create.post');
    Route::post('/store/post', [App\Http\Controllers\PostController::class, 'store'])->name('store.post');
    Route::post('/like/post', [App\Http\Controllers\PostController::class, 'like'])->name('like.post');
    Route::post('/comment/create', [App\Http\Controllers\CommentController::class, 'create'])->name('comment.create');
});


Route::get('/home/v2', [App\Http\Controllers\HomeController::class, 'indexV2'])->name('homeV2');

Route::post('sendmessage', [App\Http\Controllers\chatController::class, 'sendMessage']);

    Route::get('/t', function () {
    Predis\Autoloader::register();
    try {
        $redis = new Predis\Client();
        $redis = new Predis\Client(array(
            "scheme" => "tcp",
            "host" => "127.0.0.1"));
    } catch (Exception $e) {
        echo "Couldn't connect to Redis";
        echo $e->getMessage();
    }
    $list = $redis->keys("*");
//Optional: Sort Keys alphabetically
    sort($list);
//Loop through list of keys
    foreach ($list as $key) {
        //Get Value of Key from Redis
        $value = $redis->get($key);
        //Print Key/value Pairs
        echo "<b>Key:</b> $key <br /><b>Value:</b> $value <br /><br />";
    }
});

Route::get('/fire', function () {
    event(new \App\Events\TestEvent());
    return 'ok';
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
