@extends('layouts.admin')
@section('content')
    <!-- Remove modal-related elements -->
    <div class="border border-black rounded-lg p-4" style="background-color: rgb(255 255 255);">
        <form action="{{ route('admin.bookRoom') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="room_id" id="room_id" value="{{ $roomId }}">
            <input type="hidden" name="start_time" value="{{ request()->input('start_time') }}">
            <input type="hidden" name="end_time" value="{{ request()->input('end_time') }}">

            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.event.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title"
                    id="title" value="{{ old('title', '') }}" required>
                @if ($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.event.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                    id="description">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.description_helper') }}</span>
            </div>
            <div class="form-group" style="display: none;">
                <label for="recurring_until">Recurring until</label>
                <input class="form-control date {{ $errors->has('recurring_until') ? 'is-invalid' : '' }}" type="text"
                    name="recurring_until" id="recurring_until" value="{{ old('recurring_until') }}">
                @if ($errors->has('recurring_until'))
                    <div class="invalid-feedback">
                        {{ $errors->first('recurring_until') }}
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input class="form-control datetime" type="text" name="start_time" id="start_time"
                            value="{{ request()->input('start_time') }}"
                            placeholder="{{ trans('cruds.event.fields.start_time') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input class="form-control datetime" type="text" name="end_time" id="end_time"
                            value="{{ request()->input('end_time') }}"
                            placeholder="{{ trans('cruds.event.fields.end_time') }}" required>
                    </div>
                </div>
                {{-- <div class="col-md-3">
                        <div class="form-group">
                            <input class="form-control" type="number" name="capacity" id="capacity"
                                value="{{ request()->input('capacity') }}"
                                placeholder="{{ trans('cruds.room.fields.capacity') }}" step="1" required>
                        </div>
                    </div> --}}
                {{-- <div class="col-md-1">
                        <button class="btn btn-success">
                            Search
                        </button>
                    </div> --}}
            </div>
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#confirmationModal"
                id="confirmationButton">Submit</button>


            <!-- Confirmation Modal -->
            <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
                aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Booking</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Room:</strong> <span id="confirmationRoomID"></span></p>
                            <p><strong>Title:</strong> <span id="confirmationTitle"></span></p>
                            <p><strong>Description:</strong> <span id="confirmationDescription"></span></p>
                            <p><strong>Start Time:</strong> <span id="confirmationStartTime"></span></p>
                            <p><strong>End Time:</strong> <span id="confirmationEndTime"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Edit</button>
                            <button type="submit" class="btn btn-primary" onclick="submitForm()">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function submitForm() {
                document.getElementById('bookingForm').submit();
            }

            document.getElementById('confirmationButton').addEventListener('click', function() {
                // Get form values
                var roomId = document.getElementById('room_id').value;
                var title = document.getElementById('title').value;
                var description = document.getElementById('description').value;
                var startTime = document.getElementById('start_time').value;
                var endTime = document.getElementById('end_time').value;

                // Set modal content
                document.getElementById('confirmationRoomID').textContent = roomId;
                document.getElementById('confirmationTitle').textContent = title;
                document.getElementById('confirmationDescription').textContent = description;
                document.getElementById('confirmationStartTime').textContent = startTime;
                document.getElementById('confirmationEndTime').textContent = endTime;

                // Show modal
                $('#confirmationModal').modal('show');
            });
        });
    </script>
@endsection
