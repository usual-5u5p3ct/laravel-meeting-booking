@component('mail::message')

# Your Booking Information

**Room ID:** {{ $bookings['room_id'] }}

**Title:** {{ $bookings['title'] }}

**Start Time:** {{ \Carbon\Carbon::parse($bookings['start_time'])->format('h:i A jS F Y') }}

**End Time:** {{ \Carbon\Carbon::parse($bookings['end_time'])->format('h:i A jS F Y') }}

@endcomponent
