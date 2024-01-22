<?php

namespace App\Http\Controllers\Admin;

use App\Room;
use App\Event;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Http\Request;
use App\Services\EventService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreEventRequest;

class BookingsController extends Controller
{
    public function searchRoom(Request $request)
    {
        $rooms = null;
        if ($request->filled(['start_time', 'end_time', 'capacity'])) {
            $times = [Carbon::parse($request->input('start_time')), Carbon::parse($request->input('end_time'))];

            $rooms = Room::where('capacity', '>=', $request->input('capacity'))
                ->whereDoesntHave('events', function ($query) use ($times) {
                    $query
                        ->whereBetween('start_time', $times)
                        ->orWhereBetween('end_time', $times)
                        ->orWhere(function ($query) use ($times) {
                            $query->where('start_time', '<', $times[0])->where('end_time', '>', $times[1]);
                        });
                })
                ->get();
        }

        return view('admin.bookings.search', compact('rooms'));
    }

    public function bookRoom(Request $request, EventService $eventService)
    {
        $request->merge([
            'user_id' => auth()->id(),
        ]);

        $request->validate(
            [
                'title' => 'required',
                'room_id' => 'required',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time|room_available:' . $request->input('room_id') . ',' . $request->input('start_time') . ',' . $request->input('end_time'),
            ],
            [
                'room_available' => 'The room is not available for the selected time slot.',
            ],
        );

        $room = Room::findOrFail($request->input('room_id'));

        // if ($eventService->isRoomTaken($request->all())) {
        //     return redirect()->back()
        //             ->withInput()
        //             ->withErrors(['recurring_until' => 'This room is not available until the recurring date you have chosen']);
        // }

        // if (!auth()->user()->is_admin && !$eventService->chargeHourlyRate($request->all(), $room)) {
        //     return redirect()->back()
        //             ->withInput()
        //             ->withErrors(['Please add more credits to your account. <a href="' . route('admin.balance.index') . '">My Credits</a>']);
        // }

        $event = Event::create($request->all());

        if ($request->filled('recurring_until')) {
            $eventService->createRecurringEvents($request->all());
        }

        $userBookingDetails = [
            'room_id' => $request->input('room_id'),
            'title' => $request->input('title'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'approved' => 'Pending'
        ];

        // Send email to user
        Mail::to(auth()->user()->email)->send(new SendEmail($userBookingDetails, 'user'));

        $adminBookingDetails = [
            'id' => $event->id,
            'room_id' => $request->input('room_id'),
            'title' => $request->input('title'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'user_email' => auth()->user()->email
        ];

        // Send email to admin
        Mail::to('admin@admin.com')->send(new SendEmail($adminBookingDetails, 'admin'));

        return redirect()
            ->route('admin.systemCalendar')
            ->withStatus('A room has been successfully booked');
    }
    public function showBookingForm($roomId)
    {
        return view('admin.bookings.form', ['roomId' => $roomId]);
    }

    // public function sendEmail()
    // {
    //     Mail::to('fake@mail.com')->send(new SendEmail());

    //     return view('emails.booking');
    // }
}
