<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Alert</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F4E7D4; font-family: 'Georgia', serif;">

    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F4E7D4; padding: 40px 0;">
        <tr>
            <td align="center">

                <table role="presentation" width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border: 2px solid #BB9045; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                    <tr>
                        <td style="background-color: #6D1919; padding: 40px 20px; text-align: center;">
                            <p style="color: #BB9045; text-transform: uppercase; letter-spacing: 3px; font-size: 12px; margin: 0 0 10px 0; font-family: sans-serif; font-weight: bold;">
                                Est. 2025 • Bekasi
                            </p>
                            <h1 style="color: #F4E7D4; margin: 0; font-size: 28px; line-height: 1.2; text-transform: uppercase; letter-spacing: 1px;">
                                A New Quest Begins!
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">

                            <p style="color: #44403c; font-size: 16px; line-height: 1.6; margin-bottom: 20px; font-family: sans-serif;">
                                <strong style="color: #6D1919; font-size: 18px;">{{ $booking->customer_name }}</strong> has summoned a room. Here are the details for your preparation:
                            </p>

                            <table width="100%" cellpadding="12" cellspacing="0" style="background-color: #F4E7D4; border: 1px solid #BB9045; border-radius: 6px; margin-bottom: 30px;">

                                <tr>
                                    <td width="35%" style="border-bottom: 1px solid #dcafa8; color: #6D1919; font-weight: bold; font-family: sans-serif; text-transform: uppercase; font-size: 11px;">
                                        Quest Location
                                    </td>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #333; font-weight: bold;">
                                        {{ $booking->room->name ?? 'Standard Table' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #6D1919; font-weight: bold; font-family: sans-serif; text-transform: uppercase; font-size: 11px;">
                                        Date & ETA
                                    </td>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #333;">
                                        {{-- Safe Date Formatting --}}
                                        {{ $booking->booking_date?->format('d M Y') ?? 'Date Not Set' }}
                                        <span style="color: #666; margin: 0 5px;">@</span>
                                        {{ $booking->eta->format('H:i') ?? '--:--' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #6D1919; font-weight: bold; font-family: sans-serif; text-transform: uppercase; font-size: 11px;">
                                        Party Size
                                    </td>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #333;">
                                        {{ $booking->total_person ?? 1 }} Adventurers
                                    </td>
                                </tr>

                                <tr style="{{ $booking->need_dm ? 'background-color: #ffe4e6;' : '' }}">
                                    <td style="border-bottom: 1px solid #dcafa8; color: #6D1919; font-weight: bold; font-family: sans-serif; text-transform: uppercase; font-size: 11px;">
                                        Dungeon Master?
                                    </td>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #333; font-weight: bold;">
                                        @if($booking->need_dm)
                                            <span style="color: #b91c1c;">⚔️ YES - PREPARE LORE!</span>
                                        @else
                                            <span style="color: #666;">No (Self-Guided)</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #6D1919; font-weight: bold; font-family: sans-serif; text-transform: uppercase; font-size: 11px;">
                                        Gold (Price)
                                    </td>
                                    <td style="border-bottom: 1px solid #dcafa8; color: #333;">
                                        Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #6D1919; font-weight: bold; font-family: sans-serif; text-transform: uppercase; font-size: 11px;">
                                        Contact Scroll
                                    </td>
                                    <td style="color: #333;">
                                        <a href="https://wa.me/{{ $booking->customer_phone }}" style="color: #6D1919; text-decoration: none; font-weight: bold;">
                                            {{ $booking->customer_phone }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            @if($booking->notes)
                            <div style="background-color: #fffbeb; border-left: 4px solid #BB9045; padding: 15px; margin-bottom: 30px;">
                                <p style="margin: 0; font-size: 12px; color: #BB9045; font-weight: bold; text-transform: uppercase;">Scribe's Notes:</p>
                                <p style="margin: 5px 0 0 0; color: #555; font-style: italic;">
                                    "{{ $booking->notes }}"
                                </p>
                            </div>
                            @endif

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/admin/bookings/' . $booking->id . '/edit') }}"
                                           style="background-color: #6D1919; color: #F4E7D4; padding: 14px 30px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; border-radius: 4px; border: 2px solid #6D1919; font-family: sans-serif; display: inline-block;">
                                           Manage Booking
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #292524; padding: 20px; text-align: center;">
                            <p style="color: #BB9045; font-size: 12px; margin: 0; font-family: sans-serif;">
                                &copy; {{ date('Y') }} Natural Twenty Board Game Cafe
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
