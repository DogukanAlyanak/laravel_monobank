{{-- PROCESS --}}
{{-- ----------------------------------------------------------------------------- --}}
@extends('layouts.app')
@section('title', 'Oda Oluştur')



{{-- CONTENT --}}
{{-- ----------------------------------------------------------------------------- --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 text-center">
                <form id="createRoomForm">
                    @csrf
                    <div class="mb-3">
                        <label for="roomNameInput" class="form-label">Oda Adı</label>
                        <input type="text" class="form-control" id="roomNameInput" name="roomName" aria-describedby="roomNameDesc">
                        <div id="roomNameDesc" class="form-text"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Oluştur</button>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#createRoomForm').on('submit', function(event){
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: '{{ route("gameCreateRoomProcess"); }}',
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
