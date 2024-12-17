<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;


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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [HomeController::class, 'index'])->name('home');



Route::group(['account'], function(){

    Route::group(['middleware' => 'guest'], function(){
        Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
        Route::get('/account/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/account/process-register', [AccountController::class, 'proccessRegistration'])->name('account.proccessRegistration');
        Route::get('/account/login', [AccountController::class, 'login'])->name('account.login');
    });

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::put('/account/update', [AccountController::class, 'updateProfile'])->name('accoun.updateProfile');
        Route::post('/account/updatepic', [AccountController::class,'UpdateProfilePic'])->name('account.updatepic');
        Route::get('/account/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/account/save-job', [AccountController::class, 'saveJobs'])->name('account.savejob');
        Route::get('/account/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJob');
        Route::get('/account/edit/{jobId}', [AccountController::class, 'editJobs'])->name('account.editJob');
        Route::post('/account/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updatejob');
        Route::post('/account/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
    });

});