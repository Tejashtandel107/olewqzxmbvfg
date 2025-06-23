<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Redirect::route('login');
});

Route::get('/setup', function () {
    /*$user_id = 4;
    $user = User::find($user_id);
    if($user){
        $adminToken = $user->createToken('outsourcing-token');
        //$adminToken = $user->createToken('client-token', ['lines:store','lines:update','lines:delete','lines:show']);
        
        return [
            'adminToken'=>$adminToken->plainTextToken,
        ];
    }*/
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/account-manager.php';
require __DIR__.'/operator.php';
require __DIR__.'/client.php';
