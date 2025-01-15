<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DataTables;
class NotificationController extends Controller{
    public function index(){

        $user = Auth::user();

        if(request()->ajax()){
            $rows = Notification::query()->where('email', $user->email);

            return DataTables::of($rows)
                ->editColumn('user_id', function($r){
                    return $r->user->email;
                })
                ->editColumn('status', function($r){
                    if($r->status == 'read'){
                        return '<span class="badge bg-success">'.ucfirst($r->status).'</span>';
                    }
                    return '<span class="badge bg-danger">'.ucfirst($r->status).'</span>';
                })
                ->addColumn('action', function($r){
                    if($r->status == 'unread'){
                        return '<a href="#" class="btn btn-primary">View</a>
                                    <a href="/notifications/status/'.$r->id.'" class="btn btn-danger">Mark As Read</a>';
                    }
                    return '<a href="#" class="btn btn-primary">View</a>';
                })
                ->rawColumns(['action', 'status'])
                ->make('true');
        }

        return view('notification.index')->with([
            'user' => $user
        ]);

    }

    public function create($user_id){
        $user = User::findOrFail($user_id);

        $emails = User::query()->where('enabled', 1)->get();

        $email = [ null => 'Please select a receiver' ];

        foreach($emails as $e){
            $email[$e->email] = $e->email;
        }

        return view('notification.create')->with([
            'user' => $user,
            'email' => $email
        ]);
    }

    public function store($user_id){
        $valid = request()->validate([
            'email' => 'required|email|exists:users,email',
            'subject' => 'required|string|min:3',
            'details' => 'required|string|min:10'
        ]);

        // all ok
        $user = User::findOrFail($user_id);

        $notification = new Notification();
        $notification->user_id = $user->id;
        $notification->email = $valid['email'];
        $notification->subject = $valid['subject'];
        $notification->detail = $valid['details'];
        $notification->status = 'unread';
        $notification->datetime = now();
        $notification->save();

        // implement email send here to the receiver
        $data = [
            'user' => $user,
            'notification' => $notification
        ];

        Mail::send('notification.mail', $data, function($message) use ($notification) {
            $message->to($notification->email);
            $message->subject($notification->subject);
        });


        return redirect('/notifications')->withSuccess('Notification Has Been Sent');
    }

    public function read($id){
        $notification = Notification::findOrFail($id);

        if($notification->email !== Auth::user()->email){
            return back()->withError('Access Denied!');
        }

        if($notification->status == 'read'){
            return back()->withError('You have already read this email');
        }

        $notification->status = 'read';
        $notification->save();

        return redirect('/notifications')->withSuccess('Marked As Read');
    }

    public function readAll(){
        $notifications = Notification::query()
                ->where('status', 'unread')
                ->where('email', Auth::user()->email)
                ->get();

        if($notifications->isEmpty()){
            return back()->withError('No emails are to be read at the moment');
        }

        foreach($notifications as $n){
            $notification = Notification::findOrFail($n->id);

            $notification->status = 'read';
            $notification->save();
        }

        return redirect('/notifications')->withSuccess('All Emails Are Marked As Read');
    }
}
