<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendancereport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send an email to the admin about the attendance overall for the month of students only';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $date = date('Y-m-d 00:00:00', strtotime('-1 month'));

        $attendance = Attendance::join('users', 'users.id', '=', 'attendance.user_id')
            ->select('attendance.*', 'users.role as role')
            ->where('users.role', 'student')
            ->where('attendance.created_at', '>=', $date)
            ->get();

        $tempFile = fopen('php://temp', 'w+');

        $columnNames = ['ID', 'Name', 'Email', 'Date'];
        fputcsv($tempFile, $columnNames);

        foreach ($attendance as $a) {
            fputcsv($tempFile, [
                $a->id,
                $a->user->firstname . ' ' . $a->user->lastname,
                $a->user->email,
                $a->date,
            ]);
        }

        rewind($tempFile);

        $csvData = stream_get_contents($tempFile);
        fclose($tempFile);

        Mail::send('admin.report', [], function ($message) use ($csvData) {
            $message->to("wesleykang123@gmail.com")
                ->subject('Attendance Monthly Report')
                ->attachData($csvData, 'Attendance_Monthly_Report.csv', [
                    'mime' => 'text/csv',
                ]);
        });

    }
}
