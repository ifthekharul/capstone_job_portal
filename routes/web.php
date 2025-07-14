<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Job_detailsController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[Job_detailsController::class,'index'])->name('job_details');
Route::get('/jobs/detail/{id}',[Job_detailsController::class,'detail'])->name('jobDetailpage');
Route::post('/apply-job',[Job_detailsController::class,'applyJob'])->name('applyJob');
Route::post('/save-job',[Job_detailsController::class,'saveJob'])->name('saveJob');

Route::get('/forgot-password',[AccountController::class,'forgotPassword'])->name('account.forgotPassword');
Route::post('/process-forgot-password',[AccountController::class,'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset-password/{token}',[AccountController::class,'resetPassword'])->name('account.resetPassword');
Route::post('/process-reset-password',[AccountController::class,'processResetPassword'])->name('account.processResetPassword');

//Admin

Route::group(['prefix'=>'admin','middleware'=>'checkRole'], function(){
     Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
     Route::get('/users',[UserController::class,'index'])->name('admin.users');
     Route::get('/users/{id}',[UserController::class,'edit'])->name('admin.users.edit');
     Route::put('/users/{id}',[UserController::class,'update'])->name('admin.users.update');
     Route::delete('/users',[UserController::class,'destroy'])->name('admin.users.destroy');
     

     Route::get('/jobs',[JobController::class,'index'])->name('admin.jobs');
     Route::get('/jobs/edit/{id}',[JobController::class,'edit'])->name('admin.jobs.edit');
     Route::put('/jobs/{id}',[JobController::class,'update'])->name('admin.jobs.update');
     Route::delete('/jobs',[JobController::class,'destroy'])->name('admin.jobs.destroy');

     Route::get('/job-applications',[JobApplicationController::class,'index'])->name('admin.jobApplications');
     Route::delete('/job-applications',[JobApplicationController::class,'destroy'])->name('admin.jobApplications.destroy');
});


Route::group(['account'],function(){
    //Guest Route
    Route::group(['middleware'=> 'guest'],function(){
        Route::get('/account/registration',[AccountController::class,'registration'])->name('account.registration');
        Route::get('/account/login',[AccountController::class,'login'])->name('account.login');
        Route::get('/login', fn () => redirect()->route('account.login'))->name('login'); //secoudary login
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
       Route::post('/account/remove-save-job',[AccountController::class,'removeSavedJob'])->name('account.removeSavedJob');
    
       Route::get('/account/my-jobs',[AccountController::class,'myJobs'])->name('account.job.myJobs');
       Route::get('/account/my-jobs/edit/{jobId}',[AccountController::class,'editJobs'])->name('account.editJobs');
       Route::post('/account/update-job/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
       Route::post('/account/delete-job',[AccountController::class,'deleteJob'])->name('account.deleteJob');
       Route::get('/account/my-job-applications',[AccountController::class,'myJobApplications'])->name('account.myJobApplications');
       Route::post('/account/remove-job-application',[AccountController::class,'removeJob'])->name('account.removeJob');
       Route::get('/account/saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');


       Route::post('/account/update-password',[AccountController::class,'updatePassword'])->name('account.updatePassword');


       Route::get('/account/logout',[AccountController::class,'logout'])->name('account.logout');

        
    });
});


