@extends('layouts.guest')

@section('title', 'SIMBARA POLINDRA')

@section('content')
<div class="min-h-screen flex flex-col bg-gray-50">
    <section class="flex flex-col md:flex-row items-center px-8 py-14 gap-10">
        <div class="flex-1">
            <h1 class="text-4xl font-extrabold text-blue-900 leading-snug mb-4">
                Sistem Informasi Manajemen <br>
                Barang Milik Negara (SIMBARA)
            </h1>

            <p class="text-gray-700 text-lg mb-6">
                Platform berbasis <span class="font-semibold text-blue-900">web</span> dan
                <span class="font-semibold text-blue-900">mobile</span> untuk mempermudah proses
                pendataan, pengelolaan, dan pelaporan BMN di Politeknik Negeri Indramayu.
            </p>

            <div class="flex gap-4">
                <a href="/login"
                   class="px-6 py-3 bg-blue-900 text-white rounded-md shadow hover:bg-blue-800 transition">
                    Mulai Sekarang
                </a>

                <a href="#fitur"
                   class="px-6 py-3 border border-blue-900 text-blue-900 rounded-md shadow hover:bg-amber-400 hover:text-black transition">
                    Pelajari Fitur
                </a>
            </div>
        </div>

        <div class="flex-1 flex justify-center">
            <img src="{{ asset('images/gsc.png') }}"
                 alt="Gedung Polindra Anime"
                 class="w-full max-w-xl drop-shadow-lg rounded-lg">
        </div>

    </section>

    <section class="px-8 py-16 bg-white">
        <h2 class="text-3xl font-bold text-center text-blue-900 mb-10">
            SIMBARA Mobile
        </h2>

        <div class="flex flex-col md:flex-row items-center gap-12">
            <div class="flex justify-center flex-1">
                <img src="{{ asset('images/mobile-simbara.png') }}"
                     alt="SIMBARA Mobile App"
                     class="w-72 md:w-96 drop-shadow-xl rounded-xl">
            </div>
            <div class="flex flex-col flex-1 items-center md:items-start">
                <p class="text-gray-700 text-lg mb-6 text-center md:text-left">
                    Gunakan aplikasi mobile untuk pengecekan data BMN langsung melalui smartphone Anda.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                    <a href="#"
                       class="px-6 py-3 bg-blue-900 text-white rounded-lg font-semibold shadow hover:bg-blue-800 transition">
                        Download via Play Store
                    </a>

                    <a href="#"
                       class="px-6 py-3 bg-amber-400 text-black rounded-lg font-semibold shadow hover:bg-amber-300 transition">
                        Download via App Store
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- FITUR SECTION --}}
    <section id="fitur" class="px-8 py-16 bg-gray-50">
        <h2 class="text-3xl font-bold text-center text-blue-900 mb-10">
            Fitur Unggulan SIMBARA
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="bg-white p-6 rounded-lg shadow hover:shadow-md border-l-4 border-amber-400 transition">
                <h3 class="font-bold text-xl mb-3 text-blue-900">Pendataan BMN</h3>
                <p class="text-gray-600">
                    Menambah, memperbarui, dan mengelola data barang negara secara efisien.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow hover:shadow-md border-l-4 border-amber-400 transition">
                <h3 class="font-bold text-xl mb-3 text-blue-900">Pelacakan & Monitoring</h3>
                <p class="text-gray-600">
                    Pantau lokasi, kondisi, dan status barang secara real-time.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow hover:shadow-md border-l-4 border-amber-400 transition">
                <h3 class="font-bold text-xl mb-3 text-blue-900">Laporan Otomatis</h3>
                <p class="text-gray-600">
                    Buat laporan siap cetak dalam hitungan detik.
                </p>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-6 text-center text-blue-900 font-semibold">
        © {{ date('Y') }} SIMBARA POLINDRA — Sistem Informasi Manajemen Barang Milik Negara
    </footer>

</div>
@endsection
