<div style="font-family: 'Arial', sans-serif; background-color: #f4f4f4; padding: 20px;">
    <h1 style="color: #333; text-align: center;">Your Booking Information</h1>

    <p><strong>Room ID:</strong> {{ $bookings['room_id'] }}</p>
    <p><strong>Title:</strong> {{ $bookings['title'] }}</p>
    <p><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($bookings['start_time'])->format('h:i A jS F Y') }}</p>
    <p><strong>End Time:</strong> {{ \Carbon\Carbon::parse($bookings['end_time'])->format('h:i A jS F Y') }}</p>
</div>
