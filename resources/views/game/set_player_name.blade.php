{{-- PROCESS --}}
{{-- ----------------------------------------------------------------------------- --}}
@extends('layouts.app')
@section('title', 'Oyuncu Adım')



{{-- CONTENT --}}
{{-- ----------------------------------------------------------------------------- --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 text-center">
                <form id="playerNameForm">
                    @csrf
                    <div class="mb-3">
                        <label for="playerNameInput" class="form-label">Oyuncu Adım:</label>
                        <input type="hidden"
                            name="roomID"
                            value="{{ $room_id }}"
                        >
                        <input type="text"
                            class="form-control"
                            id="playerNameInput"
                            name="playerName"
                            aria-describedby="playerNameDesc"
                            value="{{ strval(@$player->player_name); }}"
                        >
                        <div id="playerNameDesc" class="form-text"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#playerNameForm').on('submit', function(event){
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: `{{ route("gameSetPlayerNameProcess"); }}`,
                    method: 'POST',
                    data: $(this).serialize(), // Serialize form data
                    success: function(response) {
                        // Handle success
                        console.log(response);
                        if (response.redirect) {
                            // Redirect to the URL specified in the response
                            window.location.href = response.redirect;
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error([xhr, status, error]);
                    }
                });
            });
        });
    </script>
@endsection
