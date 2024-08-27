@extends('layouts.app')
@section('title', 'Add Course To User '.$user->id)

@section('content')
<div class="container shadow">
    {!!FB::open('/users/'.$user->id.'/course/create', 'Post')!!}
    {!!FB::setErrors($errors)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Add Course to {{$user->firstname. ' '.$user->lastname}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/users/{{$user->id}}" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12">
            <?php $count = 1; ?>

            @for($i = 0 ; $i < 3 ; $i++)
                {!!FB::select('course_id_'.$count, 'Course '.$count, $course) !!}</br>
                <?php $count++?>
            @endfor
        </div>
    </div>

    {!!FB::close()!!}
</div>
@endsection
