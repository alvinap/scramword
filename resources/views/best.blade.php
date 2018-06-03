@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">
                    High Score
                    <a href="{{ route('home') }}" class="pull-right">Back to Home</a>
                </div>

                <div class="panel-body">
                    <h2>List Top Score</h2>
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NAME</th>
                                <th>SCORE</th>
                                <th>INTERVAL TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $key => $value)
                                <tr>
                                    <td align="right">{{ ++$key }}</td>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['score'] }}</td>
                                    <td>{{ $value['interval'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
