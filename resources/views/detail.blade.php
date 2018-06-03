@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Detail
                    <a href="{{ route('home') }}" class="pull-right">Back to Home</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h2>List Progress</h2>
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>WORD</th>
                                <th>SCRAMBLE</th>
                                <th>STATUS</th>
                                <th>SCORE</th>
                                <th>FILL AT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users_word as $key => $value)
                                <tr>
                                    <td align="right">{{ ++$key }}</td>
                                    <td>{{ $value->word }}</td>
                                    <td>{{ $value->scramble }}</td>
                                    <td>{{ $value->status }}</td>
                                    <td>{{ $value->score }}</td>
                                    <td>{{ $value->created_at }}</td>
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
