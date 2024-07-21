<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\Classroom;
use App\Models\Course;

class StudentCourseController extends Controller{
    public function create($id){
        $student = Student::findOrFail($id);

        $classes = Classroom::all();
        $courses = Course::query()->where('enabled', true)->orderBy('name', 'asc')->get();

        $course = [null => 'Choose/select a course'];
        $class = [null => 'Choose/select a class'];

        foreach($courses as $c){
            $course[$c->id] = $c->name. ' '.$c->year;
        }

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        return view('student_courses.create')->with([
            'student' => $student,
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

        $student = Student::findOrFail($id);

        for($i = 1 ; $i < 4 ; $i++){
            if($valid['course_'.$i] !== null and $valid['class_'.$i] !== null){
                $course_id = $valid['course_'.$i];
                $class_id = $valid['class_'.$i];

                $course = Course::findOrFail($course_id);
                $class = Classroom::findOrFail($class_id);

                $student_course = new StudentCourse();
                $student_course->student_id = $student->id;
                $student_course->class_id = $class->id;
                $student_course->course_id = $course->id;
                $student_course->save();

                $course->total_student += 1;
                $course->save();
            }

        }

        return redirect('/student/'.$student->id)->withSuccess('Course Added Successfully');

    }

    public function delete($student_course_id){

        $student_course = StudentCourse::findOrFail($student_course_id);

        $course  = Course::findOrFail($student_course->course_id);

        $student_course->delete();

        $course->total_student -= 1;
        $course->save();

        return redirect('/student/'.$student_course->student_id)->withSuccess('Course Deleted Suucessfully');
    }

    public function edit($student_course_id){
        $classes = Classroom::all();

        $student_course = StudentCourse::findOrFail($student_course_id);

        $student = Student::findOrFail($student_course->student_id);
        $course = Course::findOrFail($student_course->course_id);

        $courses = Course::query()->where('enabled', true)->get();

        $class = [null => 'Choose/Select a class'];

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        $course_array = [null => 'Choose/Select a course'];

        foreach($courses as $c){
            $course_array[$c->id] = $c->name . ' (Year'.$c->year.')';
        }

        return view('student_courses.show')->with([
            'classes' => $class,
            'courses' => $course_array,
            'student_course' => $student_course,
            'student' => $student,
            'course' => $course
        ]);
    }

    public function update($student_course_id){
        $valid = request()->validate([
            'course_id' => 'required|exists:courses,id',
            'class_id' => 'required|exists:classrooms,id'
        ]);

        $student_course = StudentCourse::findOrFail($student_course_id);

        $student_course->class_id = $valid['class_id'];
        $student_course->course_id = $valid['course_id'];
        $student_course->save();

        return redirect('/student/'.$student_course->student_id)->withSuccess('Course Updated Successfully');
    }

}
