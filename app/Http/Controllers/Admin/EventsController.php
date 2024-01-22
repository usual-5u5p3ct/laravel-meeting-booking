<?php

namespace App\Http\Controllers\Admin;

use App\Room;
use App\User;
use App\Event;
use Illuminate\Http\Request;
use App\Services\EventService;
use App\Mail\BookingStatusEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\MassDestroyEventRequest;
use Symfony\Component\HttpFoundation\Response;

class EventsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('event_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::all();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Room::all()
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        return view('admin.events.create', compact('rooms', 'users'));
    }

    public function store(StoreEventRequest $request, EventService $eventService)
    {
        if ($eventService->isRoomTaken($request->all())) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors('This room is not available at the time you have chosen');
        }

        $event = Event::create($request->all());

        if ($request->filled('recurring_until')) {
            $eventService->createRecurringEvents($request->all());
        }

        return redirect()->route('admin.events.index');
    }

    public function edit(Event $event)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Room::all()
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $event->load('room', 'user');

        return view('admin.events.edit', compact('rooms', 'users', 'event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->all());

        return redirect()->route('admin.events.index');
    }

    public function show(Event $event)
    {
        abort_if(Gate::denies('event_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->load('room', 'user');

        return view('admin.events.show', compact('event'));
    }

    public function destroy(Event $event)
    {
        abort_if(Gate::denies('event_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->forceDelete();

        return back();
    }

    public function massDestroy(MassDestroyEventRequest $request)
    {
        abort_if(Gate::denies('event_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventIds = request('ids');

        // Use forceDelete() for each individual record in the collection
        Event::whereIn('id', $eventIds)->forceDelete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function approve(Event $event)
    {
        abort_if(Gate::denies('event_approve'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->update(['approved' => true]);
        Mail::to($event->user->email)->send(new BookingStatusEmail($event, 'approved'));

        return redirect()->back()->with('success', 'Booking approved successfully!');
    }

    public function reject(Event $event)
    {
        abort_if(Gate::denies('event_reject'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->update(['approved' => false]);
        Mail::to($event->user->email)->send(new BookingStatusEmail($event, 'rejected'));

        return redirect()->back()->with('success', 'Booking rejected successfully!');
    }
}
