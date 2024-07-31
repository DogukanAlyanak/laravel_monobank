{{-- PROCESS --}}
{{-- ----------------------------------------------------------------------------- --}}
@extends('layouts.app')
@section('title', 'Ana Sayfa')



{{-- CONTENT --}}
{{-- ----------------------------------------------------------------------------- --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 text-center">
                <form>
                    <a href="{{ route('gameCreateRoom') }}" class="btn btn-primary">Oda Oluştur</a>
                </form>
            </div>
        </div>
    </div>
@endsection
