<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMBARA')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex px-2 py-2">
    @include('components.sidebar')

    <main class="flex-1 bg-gray-50 p-6">
        @include('components.header')
        <div class="mt-6">
            @yield('content')
        </div>
    </main>
</body>

</html>
