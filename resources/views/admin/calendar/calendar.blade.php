@extends('layouts.admin')
@section('content')
    @if (session('status'))
        <div id="flash-message" class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <h3 class="page-title">{{ trans('global.systemCalendar') }}</h3>
    <div class="card">
        <div class="card-header">
            {{ trans('global.systemCalendar') }}
        </div>

        <div class="card-body">
            {{-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/fullcalendar.min.css' /> --}}
            <form>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="room_id">Room</label>
                            <select class="form-control select2" name="room_id" id="room_id">
                                @foreach ($rooms as $id => $room)
                                    <option value="{{ $id }}"
                                        {{ request()->input('room_id') == $id ? 'selected' : '' }}>{{ $room }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select class="form-control select2" name="user_id" id="user_id">
                                @foreach ($users as $id => $user)
                                    <option value="{{ $id }}"
                                        {{ request()->input('user_id') == $id ? 'selected' : '' }}>{{ $user }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary mt-4">
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <div>
                <a id="roomLink" href="#"
                    class="link border rounded-lg px-2 py-2 text-white no-underline bg-gray-800" onclick="validateAndRedirect()">Book Room</a>
            </div>

            <div id='calendar'></div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            events = {!! json_encode($events) !!};

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                slotMinTime: '09:00:00',
                slotMaxTime: '20:00:00',
                initialView: 'timeGridDay',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },

                events: events,
                slotDuration: '00:15:00',

                eventContent: function(arg) {
                    return {
                        html: '<div style="font-size: 16px;">' + arg.event.title + '</div>' + '<div style="font-size: 12px;">' + arg.timeText + '</div>'
                    };
                },

                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    hour12: false
                },
            });
            calendar.render();
        });


        $(document).ready(function() {
            // Fade out the flash message after 3 seconds (3000 milliseconds)
            $('#flash-message').delay(3000).fadeOut(400);
        });

        // $(document).ready(function() {
        //     // page is now ready, initialize the calendar...
        //     events = {!! json_encode($events) !!};

        //     $('#calendar').fullCalendar({
        //         // put your options and callbacks here
        //         events: events,
        //     })
        // });

        function validateAndRedirect() {
            var selectionRoom = document.getElementById('room_id').value;

            // Check if room is selected
            if (!selectionRoom) {
                alert('Please select a room before booking!');
                return;
            }

            var roomLink = document.getElementById('roomLink');
            // update href with selected room ID
            roomLink.href = "{{ route('admin.showForm', ':room_id') }}".replace(':room_id', selectionRoom);
            // trigger link
            roomLink.click();
        }
    </script>
@stop
