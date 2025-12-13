<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMBARA')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="flex p-2 w-full h-dvh">
        @include('components.sidebar')
    
        <main class="w-full flex flex-1 flex-col bg-gray-50 p-4 overflow-y-auto overflow-x-hidden">
            @yield('content')
        </main>
    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger');
        const closeSidebar = document.getElementById('closeSidebar');

        if (hamburger && sidebar && closeSidebar) {
            hamburger.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
            });

            closeSidebar.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });
        }
    </script>
</body>

</html>
