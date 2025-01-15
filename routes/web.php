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
    Route::middleware('role:admin')->group(function(){
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
        Route::get('/users/{id}/course/create', [App\Http\Controllers\UserController::class, 'add']);
        Route::post('/users/{id}/course/create', [App\Http\Controllers\UserController::class, 'assign']);
        Route::get('/usercourse/{id}/delete',[App\Http\Controllers\UserController::class, 'delete']);

        //user timetable
        Route::get('/users/{id}/timetable/create', [App\Http\Controllers\UserTimetableController::class, 'create']);
        Route::post('/users/{id}/timetable/create', [App\Http\Controllers\UserTimetableController::class, 'store']);
        Route::get('/users/{id}/timetable/delete', [App\Http\Controllers\UserTimetableController::class, 'delete']);

        //timetable
        Route::get('/timetable', [App\Http\Controllers\TimetableController::class,'index']);
        Route::get('/timetable/create', [App\Http\Controllers\TimetableController::class,'create']);
        Route::post('/timetable/create', [App\Http\Controllers\TimetableController::class,'store']);
        Route::get('/timetable/{id}', [App\Http\Controllers\TimetableController::class,'show']);
        Route::get('/timetable/{id}/addschedule', [App\Http\Controllers\TimetableController::class, 'addschedule']);
        Route::post('/timetable/{id}/addschedule', [App\Http\Controllers\TimetableController::class, 'storeschedule']);

        //entry
        Route::get('/timetable_entry/{id}/delete', [App\Http\Controllers\TimetableController::class, 'delete']);

        // notification
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
        Route::get('/notifications/compose/{user_id}', [App\Http\Controllers\NotificationController::class, 'create']);
        Route::post('/notifications/compose/{user_id}', [App\Http\Controllers\NotificationController::class, 'store']);
        Route::get('/notifications/status/{id}', [App\Http\Controllers\NotificationController::class, 'read']);
        Route::get('/notifications/readall',  [App\Http\Controllers\NotificationController::class, 'readAll']);

    });
});

