<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Error: Page Not Fond</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .brand-logo img {
            height: 70px;
            width: 130px;
            margin: 0px;
            background-color: transparent;
            margin-top: -10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: transparent;
            /* Set background color to transparent */
            color: #ffffff;
        }

        .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .error-card {
            text-align: center;
            max-width: 600px;
        }

        .error-text {
            font-size: 6em;
            font-weight: bold;
            color: #dc3545;
        }

        .error-message {
            font-size: 1.5em;
            margin-top: 20px;
        }

        .home-link {
            margin-top: 20px;
        }

    </style>
</head>

<body>

    <div class="error-container">
        <div class="container">
            <div class="card error-card col-md-5 mx-auto p-0 m-0">
                <div class="card-header bg-transparent">
                    @if (Auth::check())
                    @if (Auth::check() && Auth::guard('webuser')->check() == true)
                    <a class="brand-logo" href="{{ route('webuser.dashboard') }}">
                    @else
                    <a class="brand-logo" href="{{ route('admin.dashboard') }}">
                    @endif
                    @else
                    <a class="brand-logo" href="{{ route('homepage') }}">
                    @endif
                        @php
                        $logo = DB::table('logos')->first();
                        @endphp

                        @if (isset($logo->logo_img) && $logo->logo_img != null)
                        <img src="{{ asset('logo_img/thumbnail/' . $logo->logo_img) }}" alt="Logo">
                        @else
                        <img src="{{asset('frontend')}}/images/logo.png" alt="Default Logo" height="80">
                        @endif
                    </a>
                </div>
                <div class="card-body">
                    <div class="error-text">404</div>
                    <div class="error-message">Oops! Page Not Fond.</div>

                    @if (Auth::check())
                        @if (Auth::check() && Auth::guard('webuser')->check() == true)
                        <a href="{{ route('webuser.dashboard') }}" class="home-link btn btn-primary">Go to Home</a>
                        @else
                        <a href="{{ route('admin.dashboard') }}" class="home-link btn btn-primary">Go to Home</a>
                        @endif
                    @else
                    <a href="{{ route('homepage') }}" class="home-link btn btn-primary">Go to Home</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>