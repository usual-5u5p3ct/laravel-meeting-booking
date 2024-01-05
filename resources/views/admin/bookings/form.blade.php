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
                    <div class="col-md-3">
                        <div class="form-group">
                            <input class="form-control" type="number" name="capacity" id="capacity"
                                value="{{ request()->input('capacity') }}"
                                placeholder="{{ trans('cruds.room.fields.capacity') }}" step="1" required>
                        </div>
                    </div>
                    {{-- <div class="col-md-1">
                        <button class="btn btn-success">
                            Search
                        </button>
                    </div> --}}
                </div>
            <button type="submit" class="btn btn-md btn-primary">Submit</button>
        </form>
    </div>
@endsection
