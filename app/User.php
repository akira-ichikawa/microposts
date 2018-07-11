<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable

{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()  //UserからMicropostをみたとき、複数存在するので、複数形micropostsでメソッドを定義
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings()  //followings が User がフォローしている User 達
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()  //followers が User をフォローしている User 達
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
    
    public function follow($userId)
{
    // 既にフォローしているかの確認
    $exist = $this->is_following($userId);
    // 自分自身ではないかの確認
    $its_me = $this->id == $userId;

    if ($exist || $its_me) {  //論理演算子 or
        // 既にフォローしていれば何もしない
        return false;
    } else {
        // 未フォローであればフォローする
        $this->followings()->attach($userId);
        return true;
    }
}

public function unfollow($userId)
{
    // 既にフォローしているかの確認
    $exist = $this->is_following($userId);
    // 自分自身ではないかの確認
    $its_me = $this->id == $userId;

    if ($exist && !$its_me) {  // 論理演算子 and
        // 既にフォローしていればフォローを外す
        $this->followings()->detach($userId);
        return true;
    } else {
        // 未フォローであれば何もしない
        return false;
    }
}

public function is_following($userId) {
    return $this->followings()->where('follow_id', $userId)->exists();
}
    
public function feed_microposts() //ここで欲しいものは、　micropostsトップページのマイクロポスト一覧（自分とフォローしている人の）
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    /*
    $this->followings()-> pluck('users.id')->toArray(); では 
    User がフォローしている User の id の配列を取得しています。 
    pluck() はテーブルとカラム名が引数として渡されて、それを全部を抜き出します。
    そして更に toArray() でただの配列に変換しています。
    更に $follow_user_ids[] = $this->id; で自分の id も追加しています。
    自分自身のマイクロポストも表示させるためです。
    最後に return Micropost::whereIn('user_id', $follow_user_ids); では、 //whereinはララベルで用意されてるもの
    microposts テーブルの user_id カラムで $follow_user_ids の中の id を含む場合に、
    全て取得して return します。
    */
    
    
    //　以下、課題２より
    
    // 2 多対多のUser用
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorite', 'user_id', 'micropost_id')->withTimestamps();
    }      
    /*
    belongsToMany() では、第一引数に得られる Model クラス (User::class) を指定し、
    第二引数に中間テーブル (user_follow) を指定し、
    第三引数に中間テーブルに保存されている自分の id を示すカラム名 (user_id) を指定し、
    第四引数に中間テーブルに保存されている関係先の id を示すカラム名 (follow_id) を指定します。
    また、 withTimestamps() は中間テーブルにも created_at と updated_at を保存する
    ためのメソッドでタイムスタンプを管理することができるようになります。
    */

public function favorite($micropostId)
{
    // 既にfavしているかの確認
    $exist = $this->is_favorite($micropostId);

    if ($exist) {
        // 既にfavしていれば何もしない
        return false;
    } else {
        // 未favであればfavする
        $this->favorites()->attach($micropostId);
        return true;
    }
}

public function unfavorite($micropostId)
{
    // 既にfavしているかの確認
    $exist = $this->is_favorite($micropostId);


    if ($exist) {
        // 既にfavしていればfavを外す
        $this->favorites()->detach($micropostId);
        return true;
    } else {
        // 未favであれば何もしない
        return false;
    }
}

public function is_favorite($micropostId) {  //既にファボしているものを返す
    return $this->favorites()->where('micropost_id', $micropostId)->exists();  //このmicropostidは？
}

}

/*
フォロー／アンフォローとは、中間テーブルのレコードを保存／削除することです。
そのために attach() と detach() というメソッドが用意されているので、それを使用します。
一応成功すれば、 return true 、失敗すれば return false を返しています。
今回実際には使用していませんが、何か成功失敗を判定したい場合には利用できます。
*/