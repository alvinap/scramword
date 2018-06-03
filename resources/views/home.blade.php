@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Home</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                Rules:
                                <ul>
                                    <li>1. There are 10 challenges in this game</li>
                                    <li>2. Correct answer, score +10</li>
                                    <li>3. Wrong answer, score -10</li>
                                    <li>4. You are considered lose, if your score less than 0</li>
                                    <li>5. You can continue game, if you back to dashboard or close your application when you are playing</li>
                                </ul>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('best') }}" class="btn btn-warning">High Score</a>
                            <hr />
                            @if ($play == 'Continue')
                                <a href="{{ route('play') }}" class="btn btn-success">Continue Game</a>
                            @else
                                <a href="{{ route('play') }}" class="btn btn-primary">Start New Game</a>
                            @endif
                        </div>
                    </div>
                    <hr />
                    <h2>History</h2>
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>STATUS</th>
                                <th>SCORE</th>
                                <th>START AT</th>
                                <th>FINISH AT</th>
                                <th>INTERVAL TIME</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $key => $value)
                                <tr>
                                    <td align="right">{{ ++$key }}</td>
                                    <td>{{ $value['status'] }}</td>
                                    <td>{{ $value['score'] }}</td>
                                    <td>{{ $value['created_at'] }}</td>
                                    <td>{{ $value['updated_at'] }}</td>
                                    <td>{{ $value['interval'] }}</td>
                                    <td align="center">
                                        @if ($value['updated_at'] == '-')
                                            <a href="{{ route('play') }}" class="btn btn-sm btn-success">Continue</a>
                                        @else
                                            <a href="{{ 'detail/'.$value['id'] }}" class="btn btn-sm btn-info btn-submit">Detail</a>
                                        @endif
                                    </td>
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
