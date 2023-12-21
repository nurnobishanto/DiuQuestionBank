<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [\App\Http\Controllers\Api\AuthController::class,'register']);
Route::post('login', [\App\Http\Controllers\Api\AuthController::class,'login']);
Route::post('logout', [\App\Http\Controllers\Api\AuthController::class,'logout']);
Route::post('refresh', [\App\Http\Controllers\Api\AuthController::class,'refresh']);
Route::post('me', [\App\Http\Controllers\Api\AuthController::class,'me']);
Route::post('semester-list', [\App\Http\Controllers\Api\MainController::class,'semester_list']);
Route::post('department-list', [\App\Http\Controllers\Api\MainController::class,'department_list']);
Route::post('year-list', [\App\Http\Controllers\Api\MainController::class,'year_list']);
Route::post('get-document', [\App\Http\Controllers\Api\MainController::class,'get_document']);
Route::post('save-document', [\App\Http\Controllers\Api\MainController::class,'save_document']);
Route::post('remove-document', [\App\Http\Controllers\Api\MainController::class,'remove_document']);
Route::post('get-save-document', [\App\Http\Controllers\Api\MainController::class,'get_save_document']);
Route::post('subscription-plans', [\App\Http\Controllers\Api\MainController::class,'subscription_plans']);
Route::post('add-subscription', [\App\Http\Controllers\Api\MainController::class,'add_subscription']);


