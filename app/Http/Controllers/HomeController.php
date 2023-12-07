<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Holyday;

class HomeController extends Controller
{


  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {

    // JSON
    $url = 'https://holidays-jp.github.io/api/v1/date.json';
    // CSV
    // $url = 'https://holidays-jp.github.io/api/v1/date.csv';

    // ストリームコンテキストのオプションを作成
    $options = array(
        // HTTPコンテキストオプションをセット
        'http' => array(
            'method'=> 'GET',
            'header'=> 'Content-type: application/json; charset=UTF-8' //JSON形式で表示
        )
    );
    
    // ストリームコンテキストの作成
    $context = stream_context_create($options);
    
    $raw_data = file_get_contents($url, false,$context);
    
    // json の内容を連想配列として $data に格納する
    $data = json_decode($raw_data,true);

    foreach ($data as $key => $value) {
      Holyday::where('day',$key)->delete();
      $new = new Holyday();
      $new->create([
        'day' => $key,
        'name' => $value,
      ]);
    }

    $user = Auth::user();
// dd($user->role);
    return view('home', [ 'role' => $user->role]);
  }

}