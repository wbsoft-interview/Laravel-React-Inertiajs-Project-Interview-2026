<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard')</title>
    {{-- style start --}}
    @include('frontend.layout.partial.style')
    {{-- style end --}}
</head>
<body>
        
    {{-- header start  --}}
    @include('frontend.layout.header')
    {{-- header end  --}}

    {{-- main content start  --}}
    @yield('frontend_content')
    {{-- main content end --}}

    {{-- footer start  --}}
    @include('frontend.layout.footer')
    {{-- footer end  --}}
    
    {{-- script start  --}}
    @include('frontend.layout.partial.script')
    {{-- script end  --}}
</body>
</html>