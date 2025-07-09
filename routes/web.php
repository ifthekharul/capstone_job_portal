<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Job_detailsController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[Job_detailsController::class,'index'])->name('job_details');
Route::get('/jobs/detail/{id}',[Job_detailsController::class,'detail'])->name('jobDetailpage');



Route::group(['account'],function(){
    //Guest Route
    Route::group(['middleware'=> 'guest'],function(){
        Route::get('/account/registration',[AccountController::class,'registration'])->name('account.registration');
        Route::get('/account/login',[AccountController::class,'login'])->name('account.login');
        Route::post('/account/process-register',[AccountController::class,'processRegistration'])->name('account.processRegistration');
        Route::post('/account/authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');

    });

    //Authenicate Route
    Route::group(['middleware'=> 'auth'],function(){
       Route::get('/account/profile',[AccountController::class,'profile'])->name('account.profile');
       Route::put('/account/update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
       Route::post('/account/update-profile-pic',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
       Route::get('/account/create-job',[AccountController::class,'createJob'])->name('account.createJob');
       Route::post('/account/save-job',[AccountController::class,'saveJob'])->name('account.saveJob');
       Route::get('/account/my-jobs',[AccountController::class,'myJobs'])->name('account.job.myJobs');
       Route::get('/account/my-jobs/edit/{jobId}',[AccountController::class,'editJobs'])->name('account.editJobs');
       Route::post('/account/update-job/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
       Route::post('/account/delete-job',[AccountController::class,'deleteJob'])->name('account.deleteJob');
       Route::post('/account/update-password',[AccountController::class,'updatePassword'])->name('account.updatePassword');


       Route::get('/account/logout',[AccountController::class,'logout'])->name('account.logout');

        
    });
});


