<?php

use App\Events\SomeEvent;
use App\Jobs\SomeJob;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('jobs/{jobs}',function($jobs){

    $user = User::find(1);

    for($i = 0;$i<$jobs;$i++){

        SomeJob::dispatch($user);

    }

    return 'Jobs processing!';


});

Route::get('/cache',function(){

  if(Cache::get('user')){
      return Cache::get('user');
  }
  Cache::put('user',User::find(1),3);

  return 'User cached for 8 seconds';

});

Route::get('/dumps',function(){

    $user1 = User::find(1)->toArray();
    // $user2 = User::find(2)->toArray();

    dump($user1);
    // dump($user2);

    return 'Dump completed';


});

Route::get('/events',function(){

    event(new SomeEvent(User::find(1)));

    return 'Event fired';
});
