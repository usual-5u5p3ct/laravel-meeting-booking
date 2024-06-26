@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                {{-- <div>
                    <a href="{{ route('admin.showForm', 1) }}"
                        class="link border rounded-lg px-2 py-2 text-white no-underline bg-gray-800">Book Room</a>
                </div>
                <div id='calendar'>
                    <select id="roomSelect" class="roomSelect">
                        <option value="room1">Room 1</option>
                        <option value="room2">Room 2</option>
                        <option value="room3">Room 3</option>
                        <option value="room4">Room 4</option>
                        <option value="room5">Room 5</option>
                    </select>
                </div> --}}
                <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>

            </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     var calendarEl = document.getElementById('calendar');
        //     var calendar = new FullCalendar.Calendar(calendarEl, {
        //         initialView: 'timeGridDay',
        //         initialDate: '2023-11-07',
        //         headerToolbar: {
        //             left: 'prev,next today',
        //             center: 'title',
        //             right: ''
        //         },

        //         events: [{
        //             title: 'First Meeting',
        //             start: '2024-01-10T00:30:00',
        //             end: '2024-01-10T01:30:00',
        //             // allDay: false
        //         }]
        //     });
        //     calendar.render();
        // });
    </script>
@endsection
