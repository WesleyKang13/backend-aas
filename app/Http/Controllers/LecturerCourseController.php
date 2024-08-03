<?php

namespace App\Http\Controllers;
use App\Models\Lecturer;
use App\Models\LecturerCourse;
use App\Models\Classroom;
use App\Models\Course;

class LecturerCourseController extends Controller{

    public function create($id){
        $lecturer = Lecturer::findOrFail($id);

        $classes = Classroom::all();
        $courses = Course::query()->where('enabled', true)->orderBy('name', 'asc')->get();

        $course = [null => 'Choose/select a course'];
        $class = [null => 'Choose/select a class'];

        foreach($courses as $c){
            $course[$c->id] = $c->name. ' - (Year '.$c->year.')';
        }

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        return view('lecturer_course.create')->with([
            'lecturer' => $lecturer,
            'class' => $class,
            'course' => $course
        ]);
    }

    public function store($id){
        $rules = [];
        for($i = 1 ; $i < 4 ; $i++){
                $rules['course_'.$i] = 'nullable|exists:courses,id';
                $rules['class_'.$i] = 'nullable|exists:classrooms,id';

        }

        $valid = request()->validate($rules);

        for($i = 1 ; $i < 4 ; $i++){
            if($valid['course_'.$i] != null and $valid['class_'.$i] == null){
                return back()->withInput()->withError('Something went wrong! Cannot left one empty.');
            }

        }

        //validate for course and class if one is left empty

        $lecturer = Lecturer::findOrFail($id);

        for($i = 1 ; $i < 4 ; $i++){
            if($valid['course_'.$i] !== null and $valid['class_'.$i] !== null){
                $course_id = $valid['course_'.$i];
                $class_id = $valid['class_'.$i];

                $course = Course::findOrFail($course_id);
                $class = Classroom::findOrFail($class_id);

                $lecturer_course = new LecturerCourse();
                $lecturer_course->lecturer_id = $lecturer->id;
                $lecturer_course->class_id = $class->id;
                $lecturer_course->course_id = $course->id;
                $lecturer_course->save();
            }

        }

        return redirect('/lecturer/'.$lecturer->id)->withSuccess('Course Added Successfully');

    }

    public function delete($lecturer_course_id){

        $lecturer_course = LecturerCourse::findOrFail($lecturer_course_id);
        $lecturer_course->delete();

        return redirect('/lecturer/'.$lecturer_course->lecturer_id)->withSuccess('Course Deleted Suucessfully');
    }

    public function edit($lecturer_course_id){
        $classes = Classroom::all();

        $lecturer_course = LecturerCourse::findOrFail($lecturer_course_id);

        $lecturer = Lecturer::findOrFail($lecturer_course->lecturer_id);

        $courses = Course::query()->where('enabled', true)->get();

        $class = [null => 'Choose/Select a class'];

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        $course_array = [null => 'Choose/Select a course'];

        foreach($courses as $c){
            $course_array[$c->id] = $c->name . ' (Year'.$c->year.')';
        }

        return view('lecturer_course.show')->with([
            'classes' => $class,
            'courses' => $course_array,
            'lecturer_course' => $lecturer_course,
            'lecturer' => $lecturer
        ]);
    }

    public function update($lecturer_course_id){
        $valid = request()->validate([
            'course_id' => 'required|exists:courses,id',
            'class_id' => 'required|exists:classrooms,id'
        ]);

        $lecturer_course = LecturerCourse::findOrFail($lecturer_course_id);

        $lecturer_course->class_id = $valid['class_id'];
        $lecturer_course->course_id = $valid['course_id'];
        $lecturer_course->save();

        return redirect('/lecturer/'.$lecturer_course->lecturer_id)->withSuccess('Course Updated Successfully');
    }
}
