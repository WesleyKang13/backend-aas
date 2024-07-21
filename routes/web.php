<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('/dashboard');
});

Route::get('/dashboard', [App\Http\Controllers\HomeController::class,'index']);

//classrooms
Route::get('/classroom', [App\Http\Controllers\ClassroomController::class,'index']);
Route::get('/classroom/create', [App\Http\Controllers\ClassroomController::class,'create']);
Route::post('/classroom/create', [App\Http\Controllers\ClassroomController::class,'store']);
Route::post('/classroom/edit/{id}', [App\Http\Controllers\ClassroomController::class,'update']);
Route::get('/classroom/{id}', [App\Http\Controllers\ClassroomController::class,'show']);

//class course
Route::get('/classcourse/create/{id}', [App\Http\Controllers\ClassroomController::class,'createCourse']);
Route::post('/classcourse/create/{id}', [App\Http\Controllers\ClassroomController::class,'storeCourse']);

//courses
Route::get('/course', [App\Http\Controllers\CourseController::class,'index']);
Route::get('/course/create', [App\Http\Controllers\CourseController::class,'create']);
Route::post('/course/create', [App\Http\Controllers\CourseController::class,'store']);
Route::get('/course/{id}/edit', [App\Http\Controllers\CourseController::class,'edit']);
Route::post('/course/{id}/edit', [App\Http\Controllers\CourseController::class,'update']);
Route::get('/course/{id}', [App\Http\Controllers\CourseController::class,'show']);

//students
Route::get('/student', [App\Http\Controllers\StudentController::class,'index']);
Route::get('/student/create', [App\Http\Controllers\StudentController::class,'create']);
Route::post('/student/create', [App\Http\Controllers\StudentController::class,'store']);
Route::post('/student/{id}/edit', [App\Http\Controllers\StudentController::class,'update']);
Route::get('/student/{id}', [App\Http\Controllers\StudentController::class,'show']);

//student course
Route::get('/studentcourse/create/{id}', [App\Http\Controllers\StudentController::class,'createCourse']);
Route::post('/studentcourse/create/{id}', [App\Http\Controllers\StudentController::class,'storeCourse']);