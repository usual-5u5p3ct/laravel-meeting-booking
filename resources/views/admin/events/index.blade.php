@extends('layouts.admin')
@section('content')
    @if (session('success'))
        <div id="flash-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @can('event_create')
        {{-- Display the "Add Event" button if the user has the "event_create" permission --}}
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.events.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.event.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.event.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            {{-- DataTable displaying the list of events --}}
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Event">
                    {{-- Table Headers --}}
                    <thead>
                        <tr>
                            {{-- Column Headers --}}
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.event.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.fields.room') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.fields.user') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.fields.title') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.fields.start_time') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.fields.end_time') }}
                            </th>
                            {{-- <th>
                                {{ trans('cruds.event.fields.description') }}
                            </th> --}}
                            <th>
                                Actions
                            </th>
                            <th>
                                Approve / Reject
                            </th>
                            <th>
                                Status
                            </th>
                        </tr>
                    </thead>
                    {{-- Table Body --}}
                    <tbody>
                        {{-- Loop through events and display its details --}}
                        @foreach ($events as $key => $event)
                            <tr data-entry-id="{{ $event->id }}">
                                {{-- Display event details in each column --}}
                                <td>

                                </td>
                                <td>
                                    {{ $event->id ?? '' }}
                                </td>
                                <td>
                                    {{ $event->room->name ?? '' }}
                                </td>
                                <td>
                                    {{ $event->user->name ?? '' }}
                                </td>
                                <td>
                                    {{ $event->title ?? '' }}
                                </td>
                                <td>
                                    {{ $event->start_time ?? '' }}
                                </td>
                                <td>
                                    {{ $event->end_time ?? '' }}
                                </td>
                                {{-- <td>
                                    {{ $event->description ?? '' }}
                                </td> --}}
                                <td>
                                    {{-- Actions column: View, Edit, Delete, Approve, Reject --}}
                                    @can('event_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.events.show', $event->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('event_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.events.edit', $event->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('event_delete')
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan
                                </td>
                                <td>
                                    @can('event_approve')
                                        {{-- <p>User has access!</p>
                                    @else
                                        <p>User does not have access!</p> --}}
                                        <form action="{{ route('admin.admin.events.approve', $event->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-xs btn-success">Approve</button>
                                        </form>
                                    @endcan

                                    @can('event_reject')
                                        {{-- <p>User has access!</p>
                                    @else
                                        <p>User does not have access!</p> --}}
                                        <form action="{{ route('admin.admin.events.reject', $event->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-xs btn-danger">Reject</button>
                                        </form>
                                    @endcan
                                </td>
                                <td>
                                    @if ($event->approved == 1)
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($event->approved == 0)
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('event_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.events.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            $('.datatable-Event:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

        $(document).ready(function() {
            // Fade out the flash message after 3 seconds (3000 milliseconds)
            $('#flash-message').delay(3000).fadeOut(400);
        });
    </script>
@endsection
