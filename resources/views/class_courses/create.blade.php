@extends('layouts.app')
@section('title', 'Add Course')

@section('content')
<div class="container shadow">
    {!! FB::open('/classcourse/create/'.$class->id, 'POST') !!}
    {!! FB::setErrors($errors) !!}
    @csrf
    <div class="row mt-4 pb-2">
        <div class="col-6">
            <h1>Add Course - {{$class->code}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/classroom" class="btn btn-secondary">Back</a>
            {!!FB::submit('Add', [], true)!!}
        </div>

        <div class="col-12">
            @for($i = 1 ; $i < 4 ; $i++)
                {!!FB::select('course_'.$i, 'Course '.$i, $course)!!}</br>

            @endfor
        </div>

    </div>

    {!!FB::close() !!}
</div>
@endsection
