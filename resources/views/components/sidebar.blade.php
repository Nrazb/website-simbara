<div id="sidebar"
    class="fixed md:relative z-40 w-25 md:w-64 bg-blue-900 text-white h-screen md:h-auto rounded-3xl flex flex-col py-4 transition-transform duration-300 transform -translate-x-full md:translate-x-0">

    <div class="relative px-3 md:px-6 mb-6">
        <button id="closeSidebar"
            class="absolute right-3 top-2 md:hidden text-white text-xl flex items-center justify-center">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-center justify-center md:justify-start gap-3 mt-6">
            <img src="{{ asset('images/polindra.png') }}" alt="Logo" class="w-10 h-10 md:w-10 md:h-10">
            <div class="hidden md:flex leading-tight">
                <span class="font-bold text-sm">SIMBARA POLINDRA</span>
            </div>
        </div>
    </div>
    <!-- Menu -->
    <div class="overflow-y-auto max-h-screen px-0 md:px-6 scrollbar-hide">
        <nav class="flex flex-col justify-between flex-1 space-y-0 md:space-y-4">
            <div class="flex flex-col space-y-0 md:space-y-4">
                <a href="/dashboard" class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg
                {{ Request::is('dashboard') ? 'bg-amber-400/10 text-yellow-300 border-l-4 border-amber-400 font-bold' : 'hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400' }}">
                    <i class="fa-solid fa-grip"></i>
                    <span class="text-xs md:text-base">Dashboard</span>
                </a>

                <a href="{{ route('item-requests.index') }}" class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg
                {{ Request::is('item-requests*') ? 'bg-amber-400/10 text-yellow-300 border-l-4 border-amber-400 font-bold' : 'hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400' }}">
                    <i class="fas fa-file-alt text-lg"></i>
                    <span class="text-xs md:text-base">Usulan barang</span>
                </a>

                <a href="{{ route('items.create') }}" class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition text-center
                {{ Request::is('items/create*') ? 'bg-amber-400/10 text-yellow-300 border-l-4 border-amber-400 font-bold' : 'hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400' }}">
                    <i class="fas fa-plus-square text-lg"></i>
                    <span class="text-xs md:text-base">Input barang</span>
                </a>

                <a href="{{ route('items.index') }}"
                class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition text-center
                {{ Request::is('items') ? 'bg-amber-400/10 text-yellow-300 border-l-4 border-amber-400 font-bold' : 'hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400' }}">
                    <i class="fas fa-box text-lg"></i>
                    <span class="text-xs md:text-base">Data Barang</span>
                </a>

                <a href="#"
                class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                        hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
                    <i class="fas fa-exchange-alt text-lg"></i>
                    <span class="text-xs md:text-base">Mutasi</span>
                </a>

                <a href="#"
                class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                        hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
                    <i class="fas fa-tools text-lg"></i>
                    <span class="text-xs md:text-base">Pemeliharaan</span>
                </a>

                <a href="#"
                class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                        hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
                    <i class="fas fa-trash text-lg"></i>
                    <span class="text-xs md:text-base">Penghapusan</span>
                </a>

                @if(Auth::user()->role === 'ADMIN')
                <a href="#"
                class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                        hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
                    <i class="fas fa-print text-lg"></i>
                    <span class="text-xs md:text-base">Cetak laporan</span>
                </a>
                @endif
            </div>
        </nav>
    </div>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
