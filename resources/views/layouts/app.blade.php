<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>@yield('title') - EMS</title>

   <meta name="csrf-token" content="{{ csrf_token() }}">

   <style>
     /* ensure html/body fill viewport */
     html, body { height: 100%; margin: 0; font-family: "Consolas", "Courier New", Courier, monospace; background-color: #eafbf6ff; }

     /* layout: header + scrollable content + footer */
     body { display: flex; flex-direction: column; min-height: 100vh; }

     /* make only the .content area scrollable */
     .content {
       flex: 1 1 auto;
       min-height: 0;               /* important for flex overflow to work in many browsers */
       overflow: auto;
       -webkit-overflow-scrolling: touch;
     }
   </style>

   <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
   <link rel="stylesheet" href="{{ asset('css/admin_view_details.css') }}">
   <link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
   <link rel="stylesheet" href="{{ asset('css/login.css') }}">
   <link rel="stylesheet" href="{{ asset('css/user_register.css') }}">
</head>
<body>
    @include('partials.header')
   <div class="content">
       @yield('content')
   </div>
    @include('partials.footer')
</body>
</html>