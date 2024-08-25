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

Route::get('/login', [App\Http\Controllers\Login\LoginController::class, 'index']);
Route::post('/login', [App\Http\Controllers\Login\LoginController::class, 'authenticate']);

Route::middleware(['userauth'])->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard']);
    Route::get('/logout', [App\Http\Controllers\Login\LoginController::class, 'logout']);

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

    //student course
    Route::get('/studentcourse/create/{id}', [App\Http\Controllers\StudentCourseController::class,'create']);
    Route::post('/studentcourse/create/{id}', [App\Http\Controllers\StudentCourseController::class,'store']);
    Route::get('/studentcourse/{id}/edit', [App\Http\Controllers\StudentCourseController::class,'edit']);
    Route::post('/studentcourse/{id}/edit', [App\Http\Controllers\StudentCourseController::class,'update']);
    Route::get('/studentcourse/{student_course_id}/delete', [App\Http\Controllers\StudentCourseController::class,'delete']);

    // user
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create']);
    Route::post('/users/create', [App\Http\Controllers\UserController::class, 'store']);
    Route::get('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit']);
    Route::post('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'update']);
    Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show']);
    Route::get('/users/{id}/s', [App\Http\Controllers\UserController::class, 'status']);

    //lecturer course
    Route::get('/lecturercourse/create/{id}', [App\Http\Controllers\LecturerCourseController::class,'create']);
    Route::post('/lecturercourse/create/{id}', [App\Http\Controllers\LecturerCourseController::class,'store']);
    Route::get('/lecturercourse/{id}/edit', [App\Http\Controllers\LecturerCourseController::class,'edit']);
    Route::post('/lecturercourse/{id}/edit', [App\Http\Controllers\LecturerCourseController::class,'update']);
    Route::get('/lecturercourse/{lecturer_course_id}/delete', [App\Http\Controllers\LecturerCourseController::class,'delete']);

    //timetable
    Route::get('/timetable', [App\Http\Controllers\TimetableController::class,'index']);
    Route::get('/timetable/create', [App\Http\Controllers\TimetableController::class,'create']);
    Route::post('/timetable/create', [App\Http\Controllers\TimetableController::class,'store']);
    Route::get('/timetable/{id}', [App\Http\Controllers\TimetableController::class,'show']);

    // student timetable
    Route::get('/student/{student_id}/timetable', [App\Http\Controllers\StudentTimetableController::class,'index']);
    Route::get('/studenttimetable/{id}/delete', [App\Http\Controllers\StudentTimetableController::class,'delete']);
    Route::get('/studenttimetable/create/{student_id}', [App\Http\Controllers\StudentTimetableController::class,'create']);
    Route::post('/studenttimetable/create/{student_id}', [App\Http\Controllers\StudentTimetableController::class,'store']);
    Route::get('/studenttimetable/{id}/edit',[App\Http\Controllers\StudentTimetableController::class,'edit']);
    Route::post('/studenttimetable/{id}/edit',[App\Http\Controllers\StudentTimetableController::class,'update']);

    //lecturer timetable
    Route::get('/lecturer/{lecturer_id}/timetable',  [App\Http\Controllers\LecturerTimetableController::class, 'index']);
    Route::get('/lecturertimetable/{id}/delete', [App\Http\Controllers\LecturerTimetableController::class, 'delete']);
    Route::get('/lecturertimetable/create/{id}', [App\Http\Controllers\LecturerTimetableController::class, 'create']);
    Route::post('/lecturertimetable/create/{id}', [App\Http\Controllers\LecturerTimetableController::class, 'store']);
    Route::get('/lecturertimetable/{id}/edit', [App\Http\Controllers\LecturerTimetableController::class, 'edit']);
    Route::post('/lecturertimetable/{id}/edit', [App\Http\Controllers\LecturerTimetableController::class, 'update']);
});

