<?php

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

Route::get('/', 'MicropostsController@index');   // /は特別なトップページやーつ

// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

// 下記ルーティングがトレイト部の少ない部分  postはデータベースにアクセスする、投げ込むイメージ
//  ルーティングは　第一引数にアドレス　に　第二引数に関数など（コントローラー・処理）
// アドレスは違うけど、ビューに必ず表示されるわけではない→リダイレクトバックがあるから、


Route::group(['middleware' => 'auth'], function (){ //このグループ内は必ずログイン認証させる
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    
    Route::group(['prefix' => 'users/{id}'], function () { //このグループ内は
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        Route::post('favorite', 'UserFavoritesController@store')->name('user.favorite');
        //１：このアドレスにPOSTメソッドでアクセスする  ここに命令をしてくれる場所はFormとかのmicropostsブレイドとか
        Route::delete('unfavorite', 'UserFavoritesController@destroy')->name('user.unfavorite');
        //Route::get('favorites', 'UsersController@favorites')->name('users.favorites');
        Route::get('favorites', 'UsersController@favorites')->name('users.favorites');
            // 第一引数の名前を適当にしても起動するか→した→。。。
    });
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});
    


