@extends('layouts.app')

@section('title', 'Input Items | SIMBARA')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Input Barang</h2>
        <p class="font-light text-gray-400 text-sm">Masukan detail barang yang diterima</p>
    </div>

    @include('components.header')
</div>
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
    <form method="POST" action="{{ route('items.store')}}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <input type="text" name="user_id" value="{{ Auth::id() }}" hidden>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code" placeholder="Masukan kode barang yang sudah ada"
                    class="w-full border border-blue-900 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    NUP <span class="text-red-500">*</span>
                </label>
                <input type="text" name="order_number" placeholder="Masukan NUP barang"
                    class="w-full border border-blue-900 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" placeholder="Masukan nama barang"
                    class="w-full border border-blue-900 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Jenis Barang <span class="text-red-500">*</span>
                </label>
                <select name="type_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
                        <option value="" disabled selected>Pilih jenis barang</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nilai BMN <span class="text-red-500">*</span>
                </label>
                <input type="" name="cost" placeholder="Masukan harga barang"
                    class="w-full border border-blue-900 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Perolehan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="acquisition_date" placeholder="Masukan tanggal perolehan barang"
                    class="w-full border border-blue-900 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tahun Perolehan <span class="text-red-500">*</span>
                </label>
                <input type="number" step="1" name="acquisition_year" placeholder="Masukan tahun perolehan barang"
                    class="w-full border border-blue-900 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Unit pemeliharaan <span class="text-red-500">*</span>
                </label>
                <select name="maintenance_unit_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
                        <option value="" disabled selected>Pilih unit pemeliharaan</option>
                    @foreach ($maintenanceUnits as $units)
                        <option value="{{ $units->id }}">{{ $units->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-end gap-3 border-t pt-4">
            <button type="submit"
                class="w-full md:w-auto px-6 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400">
                Masukan Barang
            </button>
        </div>
    </form>
</div>
@endsection
