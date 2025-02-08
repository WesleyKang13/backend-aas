@extends('layouts.app')
@section('title', 'Calendar')

@section('content')
<div class="container">
    <div class="row mt-4">
        <div class="col-12 text-center">
            <h3>
                <?php
                    $current_month = request()->get('month');
                    $next = $current_month + 1;
                    $previous = $current_month - 1;
                    $year = date('Y');

                    if($previous == 0){
                        $previous = 12;

                    }

                    if($next == 13){
                        $next = 1;
                    }

                ?>

                <a href="/timesheet?month={{$previous}}" class="btn btn-secondary mb-1 me-2"><i class="fa fa-less-than"></i></a>
                    {{date('F '.$year, strtotime($today))}}
                <a href="/timesheet?month={{$next}}" class="btn btn-secondary mb-1 ms-2"><i class="fa fa-greater-than"></i></a>

            </h3>
            </br><span class="text-muted">- It only shows the current year - </span></br>
        </div>

        {{-- make calendar for the selected month --}}
        <div class="row">
            <div class="col-12">
                <table class="table table-hover table-bordered text-center" style="background-color:antiquewhite;">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $firstDayOfMonth = strtotime(date('Y-m-01', strtotime($today)));
                            $daysInMonth = date('t', $firstDayOfMonth);
                            $startDay = date('w', $firstDayOfMonth);
                            $weeksInMonth = ceil(($daysInMonth + $startDay) / 7);
                            $currentDay = 1;
                        ?>

                        @for ($row = 0; $row < $weeksInMonth; $row++)
                            <tr>
                                @for ($col = 0; $col < 7; $col++)
                                    @if ($row === 0 && $col < $startDay)
                                        <td></td>
                                    @elseif ($currentDay > $daysInMonth)
                                        <td></td>
                                    @else
                                        <td><a href="/timesheet/list/{{$current_month}}/{{$currentDay}}" class="text-decoration-none text-dark btn btn-light w-100 ">{{ $currentDay }}</a></td>

                                        <?php $currentDay++; ?>
                                    @endif
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
