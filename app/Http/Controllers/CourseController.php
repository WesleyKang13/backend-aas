<?php

namespace App\Http\Controllers;

use App\Models\Course;
use DataTables;
class CourseController extends Controller
{
    public function index(){
        if(request() -> ajax()){
            $rows = Course::query();

            return DataTables::of($rows)
                ->editColumn('enabled', function ($r){
                    if($r->enabled == 1){
                        return '<span class="badge bg-success">Yes</span>';
                    }else{
                        return '<span class="badge bg-danger">No</span>';
                    }
                })
                ->editColumn('created_at', function($r){
                    return date('Y-M-d', strtotime($r->created_at));
                })
                ->editColumn('updated_at', function($r){
                    return date('Y-M-d', strtotime($r->updated_at));
                })
                ->addColumn('action', function($r){
                    return '<a href="/course/'.$r->id.'" class="btn btn-primary btn-sm">Manage</a>';
                })
                ->rawColumns(['enabled','action'])
                ->make('true');
        }

        return view('course.index');
    }

    public function show($id){
        $course = Course::findOrFail($id);

        return view('course.show')->with('course', $course);
    }

    public function create(){
        return view('course.create');
    }

    public function store(){
        $valid = request()->validate([
            'name' => 'required|string',
            'code' => 'required|string|min:3',
            'year' => 'required'
        ]);

        $course = new Course();

        foreach($valid as $k => $v){
            $course->{$k} = $v;
        }

        $course->total_student = 0;
        $course->save();

        return redirect('/course/'.$course->id)->withSuccess('Course Created Successfully');
    }

    public function edit($id){
        $course = Course::findOrFail($id);

        $enabled = [
            1 => 'Yes',
            0 => 'No'
        ];

        return view('course.edit')->with([
            'course' => $course,
            'enabled' => $enabled
        ]);
    }

    public function update($id){
        $valid = request()->validate([
            'name' => 'required|string',
            'code' => 'required|string|min:3',
            'total_student' => 'required|string',
            'year' => 'required|string',
            'enabled' => 'required'
        ]);

        $course = Course::findOrFail($id);

        foreach($valid as $k => $v){
            $course->{$k} = $v;
        }

        $course->save();

        return redirect('/course/'.$course->id)->withSuccess('Course Updated Succesfully');
    }

    public function import(){
        return view('course.import');
    }

    public function upload(){
        $valid = request()->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $line = 0;
        $success = [];
        $errors = [];
        $skip = [];

        if(($handle = fopen($valid['file'], "r")) !== FALSE){
            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                if($line == 0){
                    $csv = ['Course Name', 'Course Code', 'Total Student', 'Year', 'Enabled'];

                    if($data !== $csv){
                        @$errors[$line] = 'Header does not match';
                        break;
                    }

                    @$success[$line] = 'Header is valid';
                }else{

                    $course_name = $data[0];
                    $course_code = $data[1];
                    $total_student = $data[2];
                    $year = $data[3];
                    $enabled = $data[4];

                    $course = Course::query()->where('code', $course_code)->first();

                    if($course_name == null or $course_code == null or $total_student == null or $year == null or $enabled == null){
                        @$errors[$line] = 'Missing data';
                    }

                    if($course){
                        @$skip[$line] = 'Course '.$course_code.' already exists';
                    }else{
                        // up until here is fine
                        $course = new Course();
                        $course->name = $course_name;
                        $course->code = $course_code;
                        $course->total_student = $total_student;
                        $course->year = $year;
                        $course->enabled = $enabled;
                        $course->save();

                        @$success[$line] = 'Course '.$course_code.' has been created';
                    }
                }

                $line++;
            }

        }

        return view('course.import')->with([
            'success' => $success,
            'fail' => $errors,
            'skip' => $skip
        ]);
    }

    public function export(){
        $csv = '"Course Name","Course Code","Total Student","Year","Enabled"'."\n";

        $courses = Course::all();

        foreach($courses as $c){
            $csv .= '"'.$c->name.'","'.$c->code.'","'.$c->total_student.'","'.$c->year.'","'.($c->enabled == 1 ? 'Yes' : 'No').'"'."\n";
        }

        return response()->streamDownload(function() use($csv){
            echo mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
        },
        'Courses.csv',
        ['Content-Type' => 'text/csv']);
    }
}
