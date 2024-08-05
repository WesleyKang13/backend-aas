<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Classroom;
use App\Models\ClassCourse;

class ClassCourseController extends Controller {
    public function create($id){
        $class = Classroom::findOrFail($id);

        $courses = Course::query()->where('enabled', true)->orderBy('name', 'asc')->get();

        $course = [null => 'Choose/Select a course'];

        foreach($courses as $c){
            $course[$c->id] = '('.$c->code.')'.$c->name. ' - Year('.$c->year.')';
        }

        return view('class_course.create')->with([
            'class' => $class,
            'course' => $course
        ]);
    }

    public function store($id){
        $rules = [];
        for($i = 1 ; $i < 4 ; $i++){
            $rules['course_'.$i] = 'nullable|exists:courses,id';
        }

        $valid = request()->validate($rules);

        $class = Classroom::findOrFail($id);

        for($i = 1 ; $i < 4 ; $i++){
            if(isset($valid['course_'.$i])){
                $course = Course::findOrFail($valid['course_'.$i]);
                $class_course = new ClassCourse();

                $class_course->class_id = $class->id;
                $class_course->course_id = $course->id;
                $class_course->save();
            }

        }

        return redirect('/classroom/'.$class->id)->withSuccess('Courses Added Successfully');
    }

    public function show($id, $class_id){
        $class = Classroom::findOrFail($class_id);

        $class_course = ClassCourse::findOrFail($id);

        $courses = Course::query()->where('enabled', true)->get();

        $course = [null => 'Choose/Select a course'];

        foreach($courses as $c){
            $course[$c->id] = '('.$c->code.')'.$c->name. ' ( Year '.$c->year.')';
        }

        return view('class_course.show')->with([
            'course' => $course,
            'class_course' => $class_course,
            'class' => $class
        ]);
    }

    public function update($id, $class_id){
        $class = Classroom::findOrFail($class_id);

        $class_courses = ClassCourse::query()
                    ->where('class_id', $class->id)
                    ->get();

        $valid = request()->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        foreach($class_courses as $cc){
            if($cc->course_id == $valid['course_id']){
                return back()->withError('Course already exists in this class');

            }
        }

        $class_course = ClassCourse::findOrFail($id);

        $class_course->course_id = $valid['course_id'];

        $class_course->save();

        return redirect('/classroom/'.$class_course->class_id)->withSuccess('Course Updated Successfully');
    }

    public function delete($id){
        $class_course = ClassCourse::findOrFail($id);

        $class = Classroom::findOrFail($class_course->class_id);
        $class_course->delete();

        return redirect('/classroom/'.$class->id)->withSuccess('Course Deleted Successfully');
    }

}
