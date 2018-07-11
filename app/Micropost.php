<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function user() //Micropost を持つ User は1人なので、 単数形 user でメソッドを定義
    {
        return $this->belongsTo(User::class);
    }
}
    