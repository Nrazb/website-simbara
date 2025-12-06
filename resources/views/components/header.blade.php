<header class="flex items-center justify-end rounded-xl py-3 md:px-8">
    <div class="relative flex items-center">
        <div class="flex items-center rounded-xl px-2 md:px-3 py-2 cursor-pointer transition">
            <div class="relative inline-block text-left">
                <button id="userMenuButton"
                    class="flex items-center gap-3 px-2 py-1 rounded-lg hover:bg-gray-100 transition focus:outline-none">
                    <div class="w-9 h-9 rounded-full border-2 border-blue-900 flex items-center justify-center bg-white">
                        <i class="fas fa-user text-blue-900 text-base"></i>
                    </div>

                    <div class="flex flex-col leading-tight text-left">
                        <span class="font-medium text-sm text-gray-800">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                    <i id="arrowIcon" class="fas fa-chevron-up text-gray-500 text-xs transition-transform duration-300"></i>
                </button>


                <div id="userDropdown"
                    class="hidden absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                    <form method="POST" action="{{ route('logout') }}"
                        class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                        @csrf
                        <i class="fa-solid fa-arrow-right-from-bracket text-red-600"></i>
                        <button type="submit" class="w-full text-left text-red-600">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
        <button id="hamburger" class="md:hidden text-blue-900 text-2xl">
                <i class="fas fa-bars"></i>
        </button>
    </div>

    <script>
        const button = document.getElementById('userMenuButton');
        const dropdown = document.getElementById('userDropdown');
        const arrow = document.getElementById('arrowIcon');

        button.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });

        window.addEventListener('click', function (e) {
            if (!button.contains(e.target)) {
                dropdown.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        });
    </script>
</header>
