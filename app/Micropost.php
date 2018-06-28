<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    //2 追加分　多対多の関係のマイクロポスト側
    public function favored()
    {
        return $this->belongsToMany(User::class, 'favorite', 'micropost_id', 'user_id')->withTimestamps();
    }
                                     //('App\Micropost'); ？
                                     
}
    