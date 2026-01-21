<!DOCTYPE html>
<html>
<head>
    <title>New Booking Received</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2>New Booking Alert! 🎲</h2>

    <p><strong>{{ $booking->customer_name }}</strong> just booked a session.</p>

    <ul>
        <li><strong>Room:</strong> {{ $booking->room->name ?? 'Table' }}</li>
        <li><strong>Date:</strong> {{ $booking->start_time->format('d M Y, h:i A') }}</li>
        <li><strong>Duration:</strong> {{ $booking->duration_hours }} hours</li>
        <li><strong>Email:</strong> {{ $booking->email }}</li>
    </ul>

    <p>
        <a href="{{ url('/admin/bookings/' . $booking->id . '/edit') }}"
           style="background-color: #f59e0b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
           Review Booking
        </a>
    </p>

</body>
</html>
