<div class="w-20 md:w-64 bg-blue-900 text-white min-h-screen rounded-3xl flex flex-col py-6 transition-all duration-300">
    <!-- Logo & Title -->
    <div class="flex items-center justify-center md:justify-start gap-3 px-2 md:px-6 mb-6">
        <img src="{{ asset('images/polindra.png') }}" alt="Logo" class="w-10 h-10">
        <div class="hidden md:flex leading-tight">
            <span class="font-bold text-sm">SIMBARA POLINDRA</span>
        </div>
    </div>

    <!-- Menu -->
    <nav class="flex flex-col space-y-0 px-0 md:px-6 md:space-y-4">
        <a href="/dashboard"
           class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg active:font-semibold
                  hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
            <i class="fas fa-th-large text-lg"></i>
            <span class="text-xs md:text-base">Dashboard</span>
        </a>

        <a href="#"
           class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                  hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
            <i class="fas fa-file-alt text-lg"></i>
            <span class="text-xs md:text-base">Usulan barang</span>
        </a>

        <a href="#"
           class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                  hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
            <i class="fas fa-plus-square text-lg"></i>
            <span class="text-xs md:text-base">Input barang</span>
        </a>

        <a href="#"
           class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                  hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
            <i class="fas fa-box text-lg"></i>
            <span class="text-xs md:text-base">Barang</span>
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

        <a href="#"
           class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-1 md:gap-3 px-3 py-2 rounded-lg transition
                  hover:bg-amber-400/10 hover:text-yellow-300 hover:border-l-4 hover:border-amber-400 text-center">
            <i class="fas fa-print text-lg"></i>
            <span class="text-xs md:text-base">Cetak laporan</span>
        </a>
    </nav>
</div>
