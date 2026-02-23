<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/custom/css/toastr.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            /* Make the body height full viewport height */
            display: flex;
            align-items: center;
            /* Vertically center the content */
        }

        .login-container {
            margin: auto;
            /* Horizontally center the content */
        }

        .card-header {
            background-color: transparent;
            color: #007bff;
            text-align: center;
            font-weight: bold;
            border: none;
            border-bottom: 1px solid #72727245;
            padding: 25px 0px;
        }

        .card-footer {
            background-color: #f8f9fa;
            display: none;
            /* Hide the card footer */
        }

        .logo-img {
            max-width: 100%;
            height: auto;
        }

        .custom-login-form {
            margin-top: 15px;
        }

    </style>
</head>

<body>

    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        @php
                        //To get logo...
                        $softLogo = App\Models\Logo::getSoftwareLogo();
                        @endphp
                        
                        @if(isset($softLogo) && $softLogo->logo_image != null)
                        <img class="logo-img" src="{{ $softLogo->logo_image }}" alt="Default Logo">
                        @else
                        <img class="logo-img" src="{{ asset('logo_img/default/logo.png') }}" alt="Default Logo">
                        @endif
                        
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <!-- Email field with required attribute and old input value -->
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required
                                    value="{{ old('email') }}">
                            </div>
                        
                            <!-- Password field with required attribute -->
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password"
                                    required>
                            </div>

                            <div class="flex justify-between items-center mb-4 underline">
                                <a href="{{route('password-forgot')}}" class="text-sm text-gray-500 hover:underline">Forgot your
                                    password?</a>
                            </div>
                        
                            <!-- Google reCAPTCHA -->
                            <div class="mt-2 mb-2">
                                {{-- {!! NoCaptcha::renderJs() !!} --}}
                                {{-- {!! NoCaptcha::display() !!} --}}
                        
                                {{-- @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                                @endif --}}
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('backend')}}/custom/js/toastr.min.js"></script>
    {!! Toastr::message() !!}

    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error", { "progressBar": true });
            @endforeach
        @endif
    </script>

</body>

</html>