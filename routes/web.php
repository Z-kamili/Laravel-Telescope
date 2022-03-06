<?php

use App\Events\SomeEvent;
use App\Jobs\SomeJob;
use App\Mail\OrderShipped;
use App\Models\Post;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

Route::get('/exceptions',function(){

    throw new Exception('A new exception was thrown!');

});

Route::get('/posts/{post}/edit',function(Post $post,Request $request){
    return 'View for editing post';
})->middleware('can:update,post');

Route::get('/logs',function(){

    Log::emergency('Emergency');
    Log::alert('Alert');
    Log::critical('Critical');
    Log::error('Error');
    Log::warning('Warning');
    Log::notice('Notice');
    Log::info('Info');
    Log::debug('Debug');
    return 'Stuff was logged.';
    

});

Route::get('/mail',function(){

    Mail::to('someone@someone.com')->send(new OrderShipped);
    return 'Mail sent.';

});
Route::get('/notification',function(){

    $user = User::find(1);

    $user->notify(new InvoicePaid);

    return 'Notification sent';

});


