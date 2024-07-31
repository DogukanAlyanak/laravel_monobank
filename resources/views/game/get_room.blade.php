{{-- PROCESS --}}
{{-- ----------------------------------------------------------------------------- --}}
@php
    $thisRoomName = strval(@$room->room_name);
@endphp
@extends('layouts.app')
@section('title', $thisRoomName)



{{-- CONTENT --}}
{{-- ----------------------------------------------------------------------------- --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 text-center">
                <h1>Oda Adı : {{ $thisRoomName }} </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-stiped">
                    <tr>
                        <td>player name</td>
                        <td>balance</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        const redirect = {{ isset($redirect) ? "true" : "false"; }};
        $(document).ready(function(){
            if (redirect) {
                window.location.href = "{{ strval(@$redirect); }}";
            }
        });

    </script>
@endsection
