<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmployeeAjaxController;


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

Route::resource('employee-ajax-crud', EmployeeAjaxController::class);

Route::post('api/fetch-states', [EmployeeAjaxController::class, 'fetchState']);
