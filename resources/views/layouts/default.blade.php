<!DOCTYPE html>
<html lang="en">
    
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
<script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'layouts.header')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="bootstrap.css" >
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    @include('layouts.header')

    <main style="padding: 20px;">
        @yield('content')
    </main>

   
    @yield('scripts')

</body>
</html>
