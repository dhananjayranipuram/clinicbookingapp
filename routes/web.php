<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
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

Auth::routes();


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [SiteController::class, 'index']);
Route::get('/home', [SiteController::class, 'index']);
Route::get('/contact-us', [SiteController::class, 'contact']);
Route::any('/doctors', [SiteController::class, 'doctors']);
Route::any('/available-slot', [SiteController::class, 'availableSlot']);
Route::any('/get-calendar', [SiteController::class, 'getCalendar']);
Route::any('/get-appointments', [SiteController::class, 'getAppointments']);
Route::any('/make-appointment', [SiteController::class, 'makeAppointments']);
Route::any('/registration', [SiteController::class, 'registerEndUser']);
Route::any('/user-login', [SiteController::class, 'loginEndUser']);
Route::any('/logout', [SiteController::class, 'logOutEndUser']);
Route::any('/check-user', [SiteController::class, 'checkUser']);
Route::any('/calendar', [SiteController::class, 'getSelectDocCalendar']);
Route::any('/get-doctor-apointment', [SiteController::class, 'getDoctorAppointments']);
Route::any('/speciality', [SiteController::class, 'speciality']);
Route::any('/speciality-doctors', [SiteController::class, 'specDoctors']);
Route::any('/send-email', [SiteController::class, 'sendTestEmail']);

Route::get('/doctor/login', [DoctorController::class, 'login']);
Route::any('/doctor/dashboard', [DoctorController::class, 'dashboard']);
Route::any('/doctor/appointments', [DoctorController::class, 'getAppointments']);
Route::any('/doctor/profile', [DoctorController::class, 'getProfile']);
Route::any('/doctor/auth', [DoctorController::class, 'authenticateDoctor']);
Route::any('/doctor/update-profile', [DoctorController::class, 'updateProfile']);
Route::any('/doctor/change-password', [DoctorController::class, 'changePassword']);
Route::get('/doctor/logout', [DoctorController::class, 'logout']);

Route::get('/admin/login', [AdminController::class, 'login']);
Route::any('/admin/auth', [AdminController::class, 'authenticateAdmin']);
Route::any('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::any('/admin/appointments', [AdminController::class, 'getAppointments']);
Route::any('/admin/doctor-list', [AdminController::class, 'showDoctorList']);
Route::any('/admin/add-doctor', [AdminController::class, 'addDoctor']);
Route::any('/admin/edit-doctor', [AdminController::class, 'editDoctor']);
Route::any('/admin/update-doctor-profile', [AdminController::class, 'updateDoctorProfile']);

Route::any('/admin/profile', [AdminController::class, 'viewAdminProfile']);

Route::any('/admin/add-specialization', [AdminController::class, 'addSpecialization']);
Route::any('/admin/edit-specialization', [AdminController::class, 'editSpecialization']);

Route::any('/admin/add-language', [AdminController::class, 'addLanguages']);
Route::any('/admin/edit-language', [AdminController::class, 'editLanguages']);

Route::get('/admin/logout', [AdminController::class, 'logout']);

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

//To create symbolic link
Route::get('/sym-link', function () {
    Artisan::call('storage:link');
});

