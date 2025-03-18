@extends('layouts.app')
@section('title', 'Import Classrooms')

@section('content')
<div class="container-fluid">


    {!!FB::open('/classroom/import', 'Post', ['enctype' => "multipart/form-data"])!!}
    {!!FB::setErrors($errors)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Import Classrooms</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/classroom" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12">

            {!!FB::file('file', 'File')!!}<br>
        </div>

        <div class="col-12 d-flex text-center">
            <div class="col-4">
                <table border=1 class="table table-striped pb-0">
                    <tr class="table-success">
                        <th>Success</th>
                    </tr>
                </table>

            </div>

            <div class="col-4">
                <table border=1 class="table table-striped pb-0">
                    <tr class="table-danger">
                        <th>Fail</th>
                    </tr>
                </table>

            </div>

            <div class="col-4">
                <table border=1 class="table table-striped pb-0">
                    <tr class="table-warning">
                        <th>Skip</th>
                    </tr>
                </table>

            </div>

        </div>

            <div class="col-12 d-flex text-center">
                <div class="col-4">
                    <table border=1 class="table table-striped">
                        @if(isset($success) and $success !== [])
                            @foreach($success as $line => $row)
                                <tr>
                                    <td>Line {{$line. '. '.$row}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th><span class="text-muted">No Data</span></th>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="col-4">
                    <table border=1 class="table table-striped">

                        @if(isset($fail) and $fail !== [])
                            @foreach($fail as $line => $row)
                                <tr>
                                    <td>Line {{$line. '. '.$row}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th><span class="text-muted">No Data</span></th>
                            </tr>
                        @endif
                    </table>
                </div>


                <div class="col-4">
                    <table border=1 class="table table-striped">

                        @if(isset($skip) and $skip !== [])
                            @foreach($skip as $line => $row)
                                <tr>
                                    <td>Line <span class="text-dark">{{$line. '. '.$row}}</span></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th><span class="text-muted">No Data</span></th>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>

    </div>


    {!!FB::close()!!}
</div>
@endsection
