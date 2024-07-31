{{-- PROCESS --}}
{{-- ----------------------------------------------------------------------------- --}}
@php
    $roomName = strval(@$data->room_name);
@endphp
@extends('layouts.app')
@section('title', $roomName)



{{-- CONTENT --}}
{{-- ----------------------------------------------------------------------------- --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 text-center">
                <h1>Oda Adı : {{ $roomName }} </h1>
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
