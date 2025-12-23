@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Pelaporan</h2>
        <p class="font-light text-gray-400 text-sm">Cetak laporan yang diinginkan</p>
    </div>

    @include('components.header')
</div>

<div class="px-4 py-6 max-w-3xl mx-auto">
    <div class="bg-white shadow-md rounded-xl p-5 border">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Cetak Laporan</h2>

        <form action="{{ route('reports.export') }}" method="POST">
            @csrf
            <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Laporan</label>
            <select
                name="jenis_laporan"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none mb-5"
                required>
                <option value="remove">Laporan Penghapusan</option>
                <option value="mutation">Laporan Mutasi</option>
                <option value="maintenance">Laporan Maintenance</option>
                <option value="request">Laporan Usulan Barang</option>
                <option value="items">Laporan Barang</option>
            </select>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Dari Tanggal</label>
                    <input
                        type="date"
                        name="start_date"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                    <input
                        type="date"
                        name="end_date"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div class="flex gap-3 mt-3">
                <button type="submit" class="flex items-center gap-2 bg-blue-900 text-white px-5 py-2.5 rounded-lg hover:bg-amber-400 transition">
                    <i class="fa-solid fa-download"></i></i>Unduh
                </button>
            </div>
        </form>
    </div>
@endsection
