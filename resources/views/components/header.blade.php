<header class="flex items-center justify-between bg-white rounded-xl shadow-md px-4 py-3 md:px-8">
    <div class="flex items-center gap-3">
        <button id="hamburger" class="md:hidden text-blue-900 text-2xl">
            <i class="fas fa-bars"></i>
        </button>
        <div class="flex flex-col leading-tight">
            <h1 class="text-lg md:text-xl font-semibold flex items-center gap-2 px-2">
                Hello {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-400 text-xs md:text-sm px-2">Dashboard</p>
        </div>
    </div>

    <div class="flex items-center">
        <button class="hover:bg-gray-200 rounded-xl p-2 md:p-3 transition">
            <i class="fas fa-bell text-gray-700 text-base md:text-lg"></i>
        </button>
        <div class="flex items-center rounded-xl px-2 md:px-3 py-2 cursor-pointer transition">
            <div class="w-8 h-8 md:w-8 md:h-8 rounded-full border-2 border-blue-900 flex items-center justify-center bg-white">
                <i class="fas fa-user text-blue-900 text-sm md:text-lg"></i>
            </div>
            <div class="relative inline-block text-left">
                <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                    <div class="flex flex-col leading-tight text-left">
                    </div>
                    <i id="arrowIcon" class="fas fa-chevron-up text-gray-500 text-xs transition-transform duration-300"></i>
                </button>
                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-30 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                    <form method="#" action="/login" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                    @csrf
                    <i class="fa-solid fa-arrow-right-from-bracket text-red-600"></i>
                    <button type="submit" class="w-full text-left text-red-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
