@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Play
                    <a href="{{ route('home') }}" class="pull-right">Back to Home</a>
                </div>

                <div class="panel-body">
                    <h1>
                        <span>SCORE: <b id="users_score">{{ $score }}</b>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span>
                            <small>
                                TIME: 
                                <b id="intervalH" style="display: {{ (!empty($interval['hours']))?'':'none' }};">{{ (!empty($interval['hours']))?$interval['hours']:'0' }}</b>
                                <b id="intervalI" style="display: {{ (!empty($interval['minutes']))?'':'none' }};">{{ (!empty($interval['minutes']))?$interval['minutes']:'0' }}</b>
                                <b id="intervalS">{{ (!empty($interval['seconds']))?$interval['seconds']:'0' }}</b>
                            </small>
                        </span>
                        <span class="pull-right">{{ $users->name }}</span>
                    </h1>
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>STEP</th>
                                <th>WRONG WORD</th>
                                <th>CORRECT WORD</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($word as $key => $value)
                                <tr style="display: none;" id="tr-table-{{ ++$key }}">
                                    <td align="right">{{ $key }}</td>
                                    <td>
                                        @if($value['scramble_users'])
                                            {{ $value['scramble_users'] }}
                                        @else
                                            <span id="span-scramble-{{ $key }}">{{ $value['scramble'] }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($value['word_users'])
                                            <input type="text" class="form-control input-answer" value="{{ $value['word_users'] }}" disabled="true">
                                        @else
                                            <input type="hidden" class="form-control" value="{{ $value['id'] }}" id="id-word-{{ $key }}" content="{{ $key }}">
                                            <input type="text" class="form-control input-answer" id="input-answer-{{ $key }}" content="{{ $key }}">
                                        @endif
                                    </td>
                                    <td align="center">
                                        <span id="span-correct-{{ $key }}" style="display: none;">Corrected</span>
                                        <span id="span-wrong-{{ $key }}" style="display: none;">Wrong</span>
                                        <a href="#" class="btn btn-sm btn-success btn-submit" id="btn-submit-{{ $key }}" content="{{ $key }}" disabled="true">
                                            Submit
                                        </a>
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
<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Modal JS HTML-->
<script type="text/javascript">
    $(document).ready(function(){
        $("#tr-table-1").css("display", "")
        var progress = "{{ $progress }}"
        if (progress > 0) {
            for (var i = 1; i <= parseInt(progress)+1; i++) {
                $("#tr-table-" + parseInt(i)).css("display", "")
                if (i < parseInt(progress)+1) {
                    $("#input-answer-"+i).attr("disabled", true)
                    $("#btn-submit-"+i).attr("disabled", true)
                    $("#btn-submit-"+i).css("display", "none")
                    $("#span-correct-"+i).css("display", "")
                }
            }
        }

        function realTime(){
            var intervalH = $("#intervalH").text()
            var intervalI = $("#intervalI").text()
            var intervalS = $("#intervalS").text()
                intervalS = parseInt(intervalS) + 1
                if (intervalS == 60) {
                    intervalS = 0
                    intervalI = parseInt(intervalI) + 1
                    if (intervalI == 60) {
                        intervalI = 0
                        intervalH = parseInt(intervalH) + 1
                        intervalH = intervalH+' Hours'
                        $("#intervalH").css("display", "")
                        $("#intervalH").text(intervalH)
                    }
                    intervalI = intervalI+' Minutes'
                    $("#intervalI").css("display", "")
                    $("#intervalI").text(intervalI)
                }
                intervalS = intervalS+' Seconds'
            $("#intervalS").text(intervalS)
        }
        setInterval(realTime, 1000);
    })
</script>
<script type="text/javascript">
    $(function() {
        $('.input-answer').keyup(function(){
            var content = $(this).attr("content")
            var value = $(this).val()
            if (value) {
                value = value.toUpperCase()
                $(this).val(value)
                $("#btn-submit-"+content).attr("disabled", false)
            } else {
                $("#btn-submit-"+content).attr("disabled", true)
            }
        })
        $('.btn-submit').click(function(){
            var content = $(this).attr('content')
            var scramble = $("#span-scramble-"+content).text()
            var name = $("#input-answer-"+content).val()
            var id = $("#id-word-"+content).val()
            $.ajax({
                type: 'POST',
                url: '/play/post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "name": name,
                    "scramble": scramble
                },
                beforeSend: function(){
                },
                success: function(data){
                    alert(data.status)
                    if (data.status == "Correct") {
                        $("#input-answer-"+content).attr("disabled", true)
                        $("#btn-submit-"+content).attr("disabled", true)
                        $("#btn-submit-"+content).css("display", "none")
                        $("#span-correct-"+content).css("display", "")
                        $("#tr-table-" + (parseInt(content)+1)).css("display", "")
                        if (data.win == true) {
                            alert('Congrats, Your are Win :)')
                            window.location.href = "/home"
                        }
                    } else {
                        if (data.score < 0) {
                            alert('Your are lose :(')
                            window.location.href = "/home"
                            data.score = 0
                        }
                    }
                    $("#users_score").text(data.score)
                },
                error: function(data) {
                    alert('Fill the answer please')
                },
                complete: function() {
                }
            })
        })
    })
</script>
@endsection
