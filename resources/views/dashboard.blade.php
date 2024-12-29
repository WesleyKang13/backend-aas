@extends('layouts.app')
@section('Title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-12">
                <h1>Admin Dashboard - {{date('Y/m/d')}}</h1>
            </div>
        </div>

        <div class="col-12 d-flex gap-1">
            <!-- Students Histogram -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        Total Students Submitted Attendance Todaya
                    </div>
                    <div class="card-body">
                        <canvas id="studentAttendanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Lecturers Histogram -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        Total Attendance Needed To Be Submit Today
                    </div>
                    <div class="card-body">
                        <canvas id="totalAttendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    TBA
                </div>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Atque ipsum architecto quo nemo doloremque corrupti tenetur qui hic neque, nisi, dicta iusto numquam dignissimos incidunt fugiat laborum eius aperiam enim!</p>
                    <a href="#" class="btn btn-primary">Do something</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Student Attendance Data
    const studentAttendanceData = {
        labels: @json(array_column($student_attendance_chart, 'course')),
        datasets: [{
            label: 'Student Attendance',
            data: @json(array_column($student_attendance_chart, 'count')),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    // Total Attendance Data
    const totalAttendanceData = {
        labels: @json(array_column($total_attendance_chart, 'course')),
        datasets: [{
            label: 'Total Attendance',
            data: @json(array_column($total_attendance_chart, 'count')),
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };

    // Student Attendance Chart
    const studentAttendanceCtx = document.getElementById('studentAttendanceChart').getContext('2d');
    new Chart(studentAttendanceCtx, {
        type: 'bar',
        data: studentAttendanceData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Total Attendance Chart
    const totalAttendanceCtx = document.getElementById('totalAttendanceChart').getContext('2d');
    new Chart(totalAttendanceCtx, {
        type: 'bar',
        data: totalAttendanceData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
