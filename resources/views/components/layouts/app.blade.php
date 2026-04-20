@props([
    'title' => 'Dashboard',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name', 'Payroll') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">
    @stack('styles')
</head>
<body class="app-body">
    <div class="app-layout">
        @include('partials.app-nav')
        <div class="app-content-wrap">
            <main class="app-main">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
     document.addEventListener('DOMContentLoaded' , function(e){
        const flash = document.querySelector('.app-flash') ; 
        if(flash){
            setTimeout(function(){
               flash.style.display = 'none' ; 
            } , 1000);
        } 
     });
    </script>
    @stack('external_scripts')
</body>
</html>
