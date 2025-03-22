<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Timetable;
use App\Models\TimetableEntry;
use DateTime;
use DataTables;
class TimetableController extends Controller{
    public function index(){

        if(request()->ajax()){
            $rows = Timetable::query();

            return DataTables::of($rows)
                ->editColumn('class_id', function($rows){
                    return $rows->classroom->code;
                })
                ->editColumn('course_id', function($rows){
                    return $rows->course->name;
                })
                ->editColumn('created_at', function($rows){
                    return date('Y-M-d', strtotime($rows->created_at));
                })
                ->addColumn('action', function($rows){
                    return '<a href="/timetable/'.$rows->id.'" class="btn btn-primary">Manage</a>
                    <a href="/timetable/'.$rows->id.'/delete" class="btn btn-danger">Delete</a>';
                })
                ->rawColumns(['action','created_at', 'enabled'])
                ->make('true');
        }

        return view('timetable.index');
    }

    public function create(){
        $classes = Classroom::all();
        $courses = Course::query()->where('enabled', true)->get();

        $class = [null => 'Select a class'];
        $course = [null => 'Select a course'];

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        foreach($courses as $c){
            $course[$c->id] = $c->name. ' - (Year '.$c->year.')';
        }

        return view('timetable.create')->with([
            'courses' => $course,
            'classes' => $class
        ]);
    }

    public function store(){
        $valid = request()->validate([
            'class_id' => 'required|string|exists:classrooms,id',
            'course_id' => 'required|string|exists:courses,id',
            'name' => 'required|string|min:3',
            'from' => 'required|date',
            'to' => 'required|date'
        ]);

        $timetable = new Timetable();

        foreach($valid as $k => $v){
            $timetable->{$k} = $v;

        }

        $timetable->save();

        return redirect('/timetable/'.$timetable->id)->withSuccess('Timetable Created Successfully');
    }

    public function show($id){
        $timetable = Timetable::findOrFail($id);

        $entries = TimetableEntry::query()->where('timetable_id', $timetable->id)->get();

        if(request()->ajax()){
            return DataTables::of($entries)
                ->editColumn('day', function ($r){
                    return $r->daysFormat($r->day);
                })
                ->editColumn('created_at', function ($r){
                    return date('Y-m-d', strtotime($r->created_at));
                })
                ->addColumn('action', function($r){
                    return '<a href="/timetable_entry/'.$r->id.'/delete" class="btn btn-danger">Delete</a>';
                })
                ->rawColumns(['action', 'day'])
                ->make('true');
        }

        return view('timetable.show')->with('timetable', $timetable);
    }

    public function addschedule($id){
        // get timetable first
        $timetable = Timetable::findOrFail($id);

        $days = [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday'
        ];

        return view('timetable.schedule.create')->with([
            'timetable' => $timetable,
            'days' => $days
        ]);
    }

    public function storeschedule($id){
        $valid = request()->validate([
            'day' => 'required|in:mon,tue,wed,thu,fri',
            'starttime' => 'required|date_format:H:i',
            'endtime' => 'required|date_format:H:i'
        ],[
            'day' => 'Day must be only from Monday to Friday',
            'starttime' => 'Invalid time format',
            'endtime' => 'Invalid time format'
        ]);

        $timetable = Timetable::findOrFail($id);

        // check existence of entries
        $entries = TimetableEntry::query()->where('timetable_id', $timetable->id)->get();

        foreach($entries as $e){
            if($e->day == $valid['day'] and $e->starttime == $valid['starttime'] and $e->endtime == $valid['endtime']){
                return back()->withError('Same Schedule already exists for this day');
            }
        }

        $entry = new TimetableEntry();
        $entry->timetable_id = $timetable->id;
        foreach($valid as $k => $v){
            $entry->{$k} = $v;
        }

        $entry->save();

        return redirect('/timetable/'.$timetable->id)->withSuccess('Timetable entry created succesfully');
    }

    // delete entry
    public function deleteEntry($id){
        $entry = TimetableEntry::find($id);

        $timetable_id = $entry->timetable_id;
        if($entry == null){
            return back()->withError('Invalid. Schedule not found');
        }

        $entry->delete();

        return redirect('/timetable/'.$timetable_id)->withSuccess('Schedule successfully deleted');
    }

    public function import(){
        return view('timetable.import');
    }

    public function upload(){
        $valid = request()->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $line = 0;
        $success = [];
        $errors = [];
        $skip = [];
        $entries = [];

        if(($handle = fopen($valid['file'], "r")) !== FALSE){

            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                if($line == 0){
                    $csv = ['ClassCode','CourseName','Name','From','To','Mon','Tue','Wed','Thu','Fri','StartTime','EndTime'];
                    $data = str_replace(' ', '', $data);
                    if($data !== $csv){
                        @$errors[$line] = 'Header does not match. Invalid';
                        break;
                    }
                    @$success[$line] = 'Header is valid';
                }else{
                    $class_code = @trim($data[0]);
                    $course_name = @trim($data[1]);
                    $name = @trim($data[2]);
                    $from = $data[3];
                    $to = $data[4];
                    $mon = @trim($data[5]);
                    $tue = @trim($data[6]);
                    $wed = @trim($data[7]);
                    $thu = @trim($data[8]);
                    $fri = @trim($data[9]);
                    $starttime = @trim($data[10]);
                    $endtime = @trim($data[11]);


                    if($class_code == null){
                        @$errors[$line] = 'Class code is required';
                    }else if($course_name == null){
                        @$errors[$line] = 'Course name is required';
                    }else if($name == null){
                        @$errors[$line] = 'Name is required';
                    }else if($from == null){
                        @$errors[$line] = 'From date is required';
                    }else if($to == null){
                        @$errors[$line] = 'To date is required';
                    }else if($mon == null){
                        @$errors[$line] = 'Monday is required';
                    }else if($tue == null){
                        @$errors[$line] = 'Tuesday is required';
                    }else if($wed == null){
                        @$errors[$line] = 'Wednesday is required';
                    }else if($thu == null){
                        @$errors[$line] = 'Thursday is required';
                    }else if($fri == null){
                        @$errors[$line] = 'Friday is required';
                    }else if($starttime == null){
                        @$errors[$line] = 'Start time is required';
                    }else if($endtime == null){
                        @$errors[$line] = 'End time is required';
                    }

                    $class = Classroom::query()->where('code', $class_code)->first();

                    if($class == null){
                        @$errors[$line] = 'Class code not found';
                    }

                    $course = Course::query()->where('name', $course_name)->first();

                    if($course == null){
                        @$errors[$line] = 'Course name not found';
                    }

                    if($class !== null and $course !== null){
                        $timetable = Timetable::query()->where('class_id', $class->id)->where('course_id', $course->id)->where('name', $name)->first();

                        $from_parts = explode("/", $from);
                        $to_parts = explode("/", $to);

                        $from_date = "{$from_parts[2]}-{$from_parts[1]}-{$from_parts[0]}";
                        $to_date = "{$to_parts[2]}-{$to_parts[1]}-{$to_parts[0]}";

                        if($timetable !== null){
                            @$skip[$line] = 'Timetable '. $course->name . ' - ' . $name  .' already exists';
                        }else{
                            // up until here then everything is ok
                            $timetable = new Timetable();
                            $timetable->class_id = $class->id;
                            $timetable->course_id = $course->id;
                            $timetable->name = $name;
                            $timetable->from = $from_date;
                            $timetable->to = $to_date;
                            $timetable->save();

                            // check the days
                            $days = ['mon' => $mon, 'tue' => $tue, 'wed' => $wed, 'thu' => $thu, 'fri' => $fri];

                            foreach($days as $k => $v){
                                if($v == 'Yes'){
                                    $entry = new TimetableEntry();
                                    $entry->timetable_id = $timetable->id;
                                    $entry->day = $k;
                                    $entry->starttime = $starttime;
                                    $entry->endtime = $endtime;
                                    $entry->save();

                                    @$entries[$line][] = 'Created Entry For ' .$course->name. ' - ' .$name.' on '.$k.' successfully';
                                }
                            }

                            @$success[$line] = 'Timetable' .$course->name. ' - ' .$name.' created successfully';
                        }
                    }else{
                        @$errors[$line] = 'Timetable not created due to class or course not found';
                    }

                }

                $line++;

            }


        }

        return view('timetable.import')->with([
            'success' => $success,
            'fail' => $errors,
            'skip' => $skip,
            'entries' => $entries
        ]);

    }

    public function export(){
        $timetables = Timetable::all();

        $csv = '"ClassCode","CourseName","Name","From","To","Mon","Tue","Wed","Thu","Fri","StartTime","EndTime"'."\n";

        foreach($timetables as $t){
            $days = [];
            $entries = TimetableEntry::query()->where('timetable_id', $t->id)->get();
            $class = Classroom::query()->where('id', $t->class_id)->first();
            $course = Course::query()->where('id', $t->course_id)->first();
            $starttime = '';
            $endtime = '';

            foreach($entries as $e){
                $days[$e->day] = $e->day;
                $starttime = $e->starttime;
                $endtime = $e->endtime;
            }

            $csv .= '"'.$class->code.'","'.$course->name.'","'.$t->name.'","'.$t->from.'","'.$t->to.'"';

            foreach(['mon','tue','wed','thu','fri'] as $day){
                if(isset($days[$day]) and $day == $days[$day]){
                    $csv .= ',"Yes"';
                }else{
                    $csv .= ',"No"';
                }
            }

            $csv .= ',"'.$starttime.'","'.$endtime.'"'."\n";
        }

        return response()->streamDownload(function() use($csv){
            echo mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
        },
        'Timetables.csv',
        ['Content-Type' => 'text/csv']);
    }

    public function delete($id){
        $timetable = Timetable::find($id);

        if($timetable == null){
            return back()->withError('Timetable not found');
        }

        $timetable->delete();

        return redirect('/timetable')->withSuccess('Timetable deleted successfully');
    }
}
