<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alorok Bus Service</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #fff;
            margin: 0;
            padding: 5px;
            color: #000;
        }

        .invoice {
            width: 750px;
            margin: auto;
            padding: 10px;
            background: #fff;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: top;
        }

        header .info h2 {
            color: #000000;
            margin: 0;
            font-size: 26px;
        }

        header .info p {
            margin: 2px 0;
            font-size: 12px;
            color: #333;
        }

        header img {
            height: auto;
            width: 140px;
            padding-bottom: 15px;
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            max-width: 100%;
        }

        .trip-container {
            width: 75%;
            border: 1px solid #000000;
            padding: 5px;
            font-size: 12px;
        }

        .trip-header {
            border-bottom: 2px solid #000000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .trip-header p {
            margin: 5px 0;
            font-weight: bold;
        }

        .trip-details {
            display: flex;
            justify-content: space-between;
        }

        .trip-left {
            flex: 4;
        }

        .trip-right {
            flex: 2.5;
        }

        .trip-container p {
            margin: 5px 0;
        }

        .box {
            width: 25%;
            border: 1px solid #000000;
            padding: 10px;
            padding-top: 2px;
            font-size: 12px;
        }

        .box p {
            margin: 5px 0;
        }

        .passenger {
            display: flex;
            justify-content: space-between;
        }

        .passenger-left {
            flex-direction: column;
        }

        .passenger-right {
            flex-direction: column;
            width: 276px;
        }

        .passenger p {
            margin: 10px 0;
        }

        .footer-info {
            border-top: 1px solid #000000;
            margin-top: 20px;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .footer-info a {
            color: #000000;
            text-decoration: none;
            font-weight: 700;
            font-size: large;
        }

        .custom-list {
            list-style: none;
            padding-left: 0;
        }

        .custom-list li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }

        .custom-list li::before {
            content: "\f0a4";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 0;
            color: #000000;
        }

        h3 {
            text-align: center;
            color: #0c820c;
            font-size: 24px;
        }

        .terms {
            margin: 20px auto;
            padding: 15px 20px;
            border: 1px solid #20d110;
            column-count: 2;
            column-gap: 30px;
        }

        .terms ul {
            padding-left: 20px;
            margin: 0;
            font-size: 12px;
        }

        .terms li {
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .fullterms {
            border-top: 1px solid #000000;
        }

        .text-danger {
            color: rgb(204, 0, 0);
            font-weight: bold;
        }

        @media (max-width: 480px) {
            .no-print a {
                width: 100%;
                text-align: center;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

    </style>
</head>

<body>
    <div class="no-print"
        style="text-align: right; margin: 15px; display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-end;">
        <a href="{{ route('admin.dashboard') }}"
            style="display:inline-block; background-color:#f7931e; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; text-decoration:none; font-weight:600;">
            <i class="fa-solid fa-arrow-left"></i> Back To Home
        </a>

        <a href="javascript:void(0)" onclick="printNow()"
            style="display:inline-block; background-color:#28a745; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; text-decoration:none; font-weight:600;">
            <i class="fa-solid fa-print"></i> Print Now
        </a>

        {{-- <a href="{{ route('webuser.download-seat-purchase-invoice', $ticketing->id) }}"
            style="display:inline-block; background-color:#007bff; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; text-decoration:none; font-weight:600;">
            <i class="fa-solid fa-download"></i> Download PDF
        </a> --}}
    </div>

    <div class="invoice">
        <header>
            <div class="info">
                <h2>{{ $ticketing->tripData->busData->company_name ?? 'ALOROK BUS SERVICE' }}</h2>
                <p>{{ $ticketing->tripData->busData->address ?? '+880 1531117111' }}</p>
                <p>{{ $ticketing->tripData->busData->address ?? '+880 1606595757' }}</p>
                <p>{{ $ticketing->tripData->busData->city_address ?? 'rakibulhasanlotus@gmail.com' }}</p>
            </div>
            <img src="{{ asset('frontend/images/logo.png') }}" alt="Logo">
        </header>

        <div class="main-container">
            <div class="trip-container">
                <div class="trip-header">
                    <p>Trip Name: {{ $ticketing->tripData->trip_name ?? '' }}</p>
                </div>
                <div class="trip-details">
                    <div class="trip-left">
                        <p><span style="display:inline-block; width:80px;">PNR</span>:
                            {{ $ticketing->ticketing_id ?? 'N/A' }}</p>
                        <p><span style="display:inline-block; width:80px;">Bus Name</span>: <span
                                class="text-danger fw-bolder">{{ $ticketing->tripData->busData->bus_name ?? 'N/A' }}</span>
                            ({{ $ticketing->tripData->busData->busTypeData->type_name ?? 'N/A' }})</p>
                        <p><span style="display:inline-block; width:80px;">Pickup</span>:
                            {{ $ticketing->boarding_id != null ? $ticketing->boardingData->point_name : 'N/A' }}</p>
                        <p><span style="display:inline-block; width:80px;">Dropping</span>:
                            {{ $ticketing->dropping_id != null ? $ticketing->droppingData->point_name : 'N/A' }}</p>
                    </div>
                    <div class="trip-right">

                        <p><span style="display:inline-block; width:80px;">Journey Date</span>:
                            {{ Carbon\Carbon::parse($singleTripData->deperture_date)->format('d-m-Y') }}
                            {{ Carbon\Carbon::create($singleTripData->deperture_time)->format('h:i') }}</p>
                        <p><span style="display:inline-block; width:80px;">Arrival Date</span>:
                            {{ Carbon\Carbon::parse($singleTripData->arrival_date)->format('d-m-Y') }}
                            {{ Carbon\Carbon::create($singleTripData->arrival_time)->format('h:i') }}</p>
                        <p><span style="display:inline-block; width:80px;">Return Date</span>:
                            {{ Carbon\Carbon::parse($singleTripData->return_date)->format('d-m-Y') }}
                            {{ Carbon\Carbon::create($singleTripData->return_time)->format('h:i') }}</p>
                        <p><span style="display:inline-block; width:80px;">Booked By</span>:
                            {{ $ticketing->ticketing_by ? Str::title(optional($ticketing->ticketingByData)->name) : 'Online' }}
                        </p>

                        @if($ticketing->is_ticketing_cancel == true)
                        <p><strong>Cancel By :</strong>
                            {{ Str::title($ticketing->cancelTicketingByData->name) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="box">
                <p style="font-size: 16px;"><span
                        style="display:inline-block; width:150px; border-bottom: 2px solid #000000; padding-bottom: 7px;">Payment
                        Details</span></p>
                @if($ticketing->booking_type == 1)
                <p><span style="display:inline-block; width:110px;">Ticket Price</span>:
                    {{ number_format($ticketing->ticketingBillPayData->net_amount ?? 0, 2) }}</p>
                <p><span style="display:inline-block; width:110px;">Service Charge (2%)</span>:
                    {{ number_format($ticketing->ticketingBillPayData->charge_amount ?? 0, 2) }}</p>
                <p><span style="display:inline-block; width:110px;">Total Fee</span>:
                    {{ number_format($ticketing->ticketingBillPayData->total_amount ?? 0, 2) }}</p>
                @else
                <p><span style="display:inline-block; width:110px;">Ticket Status</span>: Unpaid</p>
                @endif
            </div>
        </div>

        <div class="passenger">
            <div class="passenger-left">
                <p><strong>Passenger :</strong> {{ $ticketing->contact_name ?? 'N/A' }} <span>-
                        {{$ticketing->contact_gender == 'Male' ? 'M' : 'F'}} </span></p>
                <p><strong>Mobile No :</strong> {{ $ticketing->contact_number ?? 'N/A' }}</p>
                <p><strong>Whatsapp :</strong> {{ $ticketing->contact_whatsapp ?? 'N/A' }}</p>

            </div>
            <div class="passenger-right">
                <p><strong>Seat(s) :</strong>
                    @foreach($userTickets as $seat)
                    <span>[{{ strtoupper($seat->seatPlanDetailData->seat_name) }}]</span>,
                    @endforeach
                </p>
            </div>
        </div>

        <div class="footer-info">
            <p>Powered by :<br> <a href="#">alorokbusservice.com</a></p>
            <p>Email:<br><a href="#">rakibulhasanlotus@gmail.com</a> </p>
            <p>Call us:<br><a>+880 1531117111</a> </p>
            <img src="{{ asset('frontend/images/logo.png') }}" alt="Logo" width="70" height="50">
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <div class="fullterms">
            <h3>Terms & Conditions</h3>
            <div class="terms">
                <ul class="custom-list">
                    <li>Keep a print copy or PDF of the ticket with you.</li>
                    <li>The Bus number, guide name, number, and all tour information will be sent to the WhatsApp number
                        24 hours before the start of the journey. So, provide your WhatsApp number carefully and keep it
                        active.</li>
                    <li>The ticket is non-refundable and non-transferable.</li>
                    <li>You must be present at the departure point at least 30 minutes before the journey.</li>
                    <li>The bus will make a break on route.</li>
                    <li>Smoking is strictly prohibited during the journey.</li>
                    <li>The journey does not include any accident insurance.</li>
                    <li>The authority may change the starting point of the journey due to any circumstances, and this
                        change will be notified by message at least 24 hours before the journey.</li>
                    <li>The authority is not responsible for carrying illegal goods. In any adverse situation, the
                        authority will fully cooperate with law enforcement agencies.</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $( document ).ready(function() {
                $(function () {
                    'use strict';
                    window.print();
                });
            });

        function printNow() {
            window.print();
        }
    </script>
</body>

</html>