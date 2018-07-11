<?php
/**
create アクションや store アクションが不要なのは、それらは RegisterController が担ってくれたからです。
ここでは index と show のみを実装します。 User に関する Controller が2つある形になります。
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; // 追加
use App\Micropost; // 追加

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return view('users.index', [
            'users' => $users,
        ]);
    }
    
    public function show($id)
    {
        $user = User::find($id);  //findで一人を探して実質newして$user→new＝メモリに保存する感じ
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);  //
// microposts変数の定義
        $data = [
            'user' => $user,
            'microposts' => $microposts,
        ];

        $data += $this->counts($user);

        return view('users.show', $data);
    }
    
    public function followings($id)
    {
        $user = User::find($id);
        $followings = $user->followings()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followings,
        ];

        $data += $this->counts($user);

        return view('users.followings', $data);
    }

    public function followers($id)
    {
        $user = User::find($id);
        $followers = $user->followers()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followers,
        ];

        $data += $this->counts($user);

        return view('users.followers', $data);
    }
    
    public function favorites($id)
    {
        $user = User::find($id);
        $favorites = $user->favorites()->paginate(10);  //このfavoritesはuserクラスのfavorites関数
        
        $data = [   //配列
            'user' => $user,
            'microposts' => $favorites,  //このfavoritesはどんな形で帰ってくるのか、配列？
        ];
            
        $data += $this->counts($user);
        
        return view('users.favorites', $data);
        
    }
    
}

/*


*/