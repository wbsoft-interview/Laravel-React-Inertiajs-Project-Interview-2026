<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Package Renewal Invoice</title>
    <link rel="stylesheet" href="{{asset('frontend')}}/css/toastr.min.css">
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
            padding: 15px;
            background: #fff;
        }

        header {
            display: flex;
            justify-content: space-between;
        }

        header h2 {
            margin: 0;
            font-size: 26px;
        }

        header p {
            margin: 3px 0;
            font-size: 13px;
        }

        .section {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 15px;
            font-size: 13px;
        }

        .section h4 {
            margin: 0 0 8px 0;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }

        .row p {
            margin: 6px 0;
        }

        .text-danger {
            color: #c00;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }

        @media print {
            .no-print {
                display: none;
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
        style="display:inline-block; background-color:#007bff; color:white; border:none; padding:8px 16px;
        border-radius:6px; cursor:pointer; text-decoration:none; font-weight:600;">
        <i class="fa-solid fa-download"></i> Download PDF
        </a> --}}
    </div>

    <div class="invoice">

        <!-- Header -->
        <header>
            <div>
                <h2>{{ config('app.name') }}</h2>
                <p>Package Renewal Invoice</p>
                <p>Date: {{ now()->format('d-m-Y h:i A') }}</p>
            </div>
            <img src="{{asset('backend')}}/billing_invoice_logo.png" class="img-fluid" height="40">
        </header>

        <!-- Customer Info -->
        <div class="section">
            <h4>Information</h4>
            <div class="row">
                <div>
                    <p><strong>Name:</strong> {{ $ticketing->packageByData->name ?? 'N/A' }}</p>
                    <p><strong>Mobile:</strong> {{ $ticketing->packageByData->login_mobile ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $ticketing->packageByData->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>Assigned From:</strong> {{ $ticketing->assignedData->name }}</p>
                    <p><strong>Mobile:</strong> {{ $ticketing->assignedData->mobile }}</p>
                    <p><strong>Email:</strong> {{ $ticketing->assignedData->email }}</p>
                </div>
            </div>
        </div>

        <!-- Package Info -->
        <div class="section">
            <h4>Package Details</h4>
            <div class="row">
                <div>
                    <p><strong>Package Name:</strong> {{ $ticketing->packageData->package_name ?? 'N/A' }}</p>
                    <p><strong>SMS Quantity:</strong> {{ $ticketing->sms_qty }}</p>
                    <p><strong>Student Limit:</strong> {{ $ticketing->student_qty }}</p>
                </div>
                <div>
                    <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($activePackage->start_date)->format('d M Y') }}</p>
                    <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($activePackage->end_date)->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="section">
            <h4>Payment Information</h4>
            <div class="row">
                <div>
                    <p><strong>Payment Status:</strong>
                        {{ $ticketing->is_ticketing_pay ? 'Paid' : 'Unpaid' }}
                    </p>
                    <p><strong>Ticketing Status:</strong>
                        {{ $ticketing->ticketing_status ? 'Completed' : 'Pending' }}
                    </p>
                    <p><strong>Invoice ID:</strong>
                        #PKG-{{ $ticketing->id }}
                    </p>
                </div>
                <div>
                    <p><strong>Processed By:</strong>
                        {{ optional($ticketing->assignedData)->name ?? 'System' }}
                    </p>
                    <p><strong>Package Given By:</strong>
                        {{ optional($ticketing->packageByData)->name ?? 'Admin' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Powered By<br><strong>{{ config('app.name') }}</strong></p>
            <p>Support<br><strong>{{$ticketing->assignedData->email}}</strong></p>
            <p>Invoice ID<br><strong>#PKG-{{ $ticketing->id }}</strong></p>
        </div>

    </div>

    {{-- <script>
        window.onload = function () {
        window.print();
    }
    </script> --}}

    <script>
        function printNow() {
        window.print();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            timer: 4000,
            showConfirmButton: false
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            timer: 4000,
            showConfirmButton: false
        });
    </script>
    @endif
</body>

</html>