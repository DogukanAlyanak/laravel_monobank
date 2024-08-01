{{-- PROCESS --}}
{{-- ----------------------------------------------------------------------------- --}}
@php
    $thisRoomName = strval(@$room->room_name);
    // $thisPlayer
@endphp
@extends('layouts.app')
@section('title', $thisRoomName)



{{-- CONTENT --}}
{{-- ----------------------------------------------------------------------------- --}}
@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-grid gap-2 col-6 mx-auto">
                    <a type="button" href="{{ route('gameCreateRoom') }}" class="btn btn-dark btn-sm">
                        Yeni Oyun
                    </a>
                    <button id="invitePlayerActionButton" type="button" class="btn btn-dark btn-sm">
                        Davet Et
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-4 text-center">
                <h1>Oda Adı : {{ $thisRoomName }} </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table id="bankAccountsTable" class="table table-stiped table-dark">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Full screen modal -->
    <div class="modal fade" id="invitePlayerModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal Başlığı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Modal içeriği buraya gelecek.
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        const redirect = {{ isset($redirect) ? 'true' : 'false' }};
        $(document).ready(function() {
            if (redirect) {
                window.location.href = "{{ strval(@$redirect) }}";
            } else {

                // --- GAME TIMING ----------------------------------------
                getAccounts()
                var gameTiming = setInterval(() => {
                    getAccounts()
                }, 2500);
            }
        });

        @if (isset($redirect) == false)
            function getAccounts() {
                $.ajax({
                    url: '{{ route('gameGetPlayerAccountsFromRoom') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': `{{ csrf_token() }}`
                    },
                    data: {
                        room_id: `{{ strval(@$room->id) }}`
                    },
                    success: function(response) {

                        // Handle success
                        console.log(response);

                        sortBankAccounts(response.data)
                    },
                    error: function(xhr, status, error) {

                        // Handle error
                        console.error([xhr, status, error]);
                    }
                });
            }


            var currentData = []

            function sortBankAccounts(data) {
                if (JSON.stringify(data) != JSON.stringify(currentData)) {
                    currentData = data
                    let table = $(`#bankAccountsTable`).children(`tbody`)
                    let content = ''
                    $.each(data, function(i, item) {

                        let thisPlayerMark = ""
                        if (`{{ strval(@$thisPlayer->session_token); }}` == item.player_session_token) {
                            thisPlayerMark = "*"
                        }

                        content += `<tr data-account-id="${item.id}"
                                        data-player-token="${item.player_session_token}"
                                        data-balance-value="${item.balance}">
                                <td>${thisPlayerMark}${item.player_name}</td>
                                <td class="text-right">${formatCurrency(item.balance)}</td>
                            </tr>`

                        if (i == data.length - 1) {
                            table.html(content)
                        }
                    })
                }
            }


            function formatCurrency(value) {

                // değeri float değere sabitle
                value = parseFloat(value);

                if (typeof value != "number") {
                    return value;
                }

                // Değeri sabit bir noktada keserek iki ondalık basamağa yuvarla
                var formattedValue = value.toFixed(2);

                // Binlik ayraç ekle
                formattedValue = formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                // Virgül ile ondalık basamakları ayır
                formattedValue = formattedValue.replace(".", ",");

                // Değeri TL simgesi ile birleştir
                formattedValue = formattedValue.replace(/,(\d{2})$/, ',$1 ₺');

                return formattedValue;
            }

            $(document).on(`click`, `#invitePlayerActionButton`, function() {
                $(`#invitePlayerModal`).modal(`show`)
            })


            function setPlayerBanker(playerToken) {
                $.ajax({
                    url: '{{ route('gameSetPlayerBanker') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': `{{ csrf_token() }}`
                    },
                    data: {
                        player_token: playerToken,
                        room_id: `{{ $room->id; }}`
                    },
                    success: function(response) {

                        // Handle success
                        console.log(response);
                    },
                    error: function(xhr, status, error) {

                        // Handle error
                        console.error([xhr, status, error]);
                    }
                });
            }
        @endif
    </script>
@endsection
