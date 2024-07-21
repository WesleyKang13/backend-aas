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
Route::get('/classcourse/create/{id}', [App\Http\Controllers\ClassCourseController::class,'create']);
Route::post('/classcourse/create/{id}', [App\Http\Controllers\ClassCourseController::class,'store']);
Route::get('/classcourse/{id}/class/{class_id}', [App\Http\Controllers\ClassCourseController::class,'show']);
Route::post('/classcourse/{id}/edit/{class_id}', [App\Http\Controllers\ClassCourseController::class,'update']);
Route::get('/classcourse/{id}/delete', [App\Http\Controllers\ClassCourseController::class,'delete']);


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
Route::get('/student/{id}/status', [App\Http\Controllers\StudentController::class,'status']);

//student course
Route::get('/studentcourse/create/{id}', [App\Http\Controllers\StudentCourseController::class,'create']);
Route::post('/studentcourse/create/{id}', [App\Http\Controllers\StudentCourseController::class,'store']);
Route::get('/studentcourse/{id}/edit', [App\Http\Controllers\StudentCourseController::class,'edit']);
Route::post('/studentcourse/{id}/edit', [App\Http\Controllers\StudentCourseController::class,'update']);
Route::get('/studentcourse/{student_course_id}/delete', [App\Http\Controllers\StudentCourseController::class,'delete']);

//lecturer
Route::get('/lecturer', [App\Http\Controllers\LecturerController::class, 'index']);
Route::get('/lecturer/create', [App\Http\Controllers\LecturerController::class, 'create']);
Route::post('/lecturer/create', [App\Http\Controllers\LecturerController::class, 'store']);
Route::get('/lecturer/{id}', [App\Http\Controllers\LecturerController::class, 'show']);
Route::get('/lecturer/{id}/status', [App\Http\Controllers\LecturerController::class,'status']);

//lecturer course
