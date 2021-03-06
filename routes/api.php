<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/torrent',function(Request $request){
if($request->has('term')){
  return response()->json(\App\Torrent::where('name','like',"%{$reqest->term}%")->paginate(25));
}
else{
  return response()->json(\App\Torrent::orderBy('created_at','desc')->paginate(25));
}
});
Route::get('/search',function(Request $request){
  header('Content-Type: application/json');
if($request->q) {
  $page = file_get_contents('https://thepiratebay.org/search/'.urlencode($request->q).'/0/99/0');
  preg_match_all('/<div class="detName">.*?<a href=".*?" class="detLink" title=".*?">(.*?)<\/a>\n<\/div>\n<a href="(.*?)" title="Download this torrent using magnet"><img src=".*?" alt="Magnet link" \/><\/a>.*?<img src=".*?>\n.*?<font class="detDesc">Uploaded (.*?), Size (.*?), ULed by.*?<\/font>\n.*?<\/td>\n.*?<td align="right">(.*?)<\/td>\n.*?<td align="right">(.*?)<\/td>/', $page, $links);
  $result = [];
  for($i = 0; $i<count($links[0]); $i++) {
    $result[] = ['title' => $links[1][$i], 'magnet' => $links[2][$i], 'size' => str_replace('&nbsp;', ' ', $links[4][$i]), 'date' => str_replace('&nbsp;', ' ', $links[3][$i]), 'seeders' => $links[5][$i], 'leechers' => $links[6][$i]];
  }
  if($result === []) {
    exit('{"error":"nothing found"}');
  }
  exit(json_encode($result));
}
exit('{"error":"parameter q is required"}');
});
