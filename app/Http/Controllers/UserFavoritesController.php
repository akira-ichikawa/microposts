<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFavoritesController extends Controller
{
    public function store(Request $request, $micropostId)  //ルーターの
    {
        \Auth::user()->favorite($micropostId);  //ログイン中のユーザーを取得、関数起動
        //このユーザーはUserクラスのことで良い？
        return redirect()->back();
    }

    public function destroy($micropostId)
    {
        \Auth::user()->unfavorite($micropostId);
        return redirect()->back();
    }
}



