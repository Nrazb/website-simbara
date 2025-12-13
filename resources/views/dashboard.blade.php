@extends('layouts.app')

@section('title', 'Dashboard | SIMBARA')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="font-semibold text-blue-900 text-xl">Halo {{ auth()->user()->name }} 👋</h2>
            <p class="font-light text-gray-400 text-sm">Selamat datang di dashboard</p>
        </div>

        @include('components.header')
    </div>
    <div class="mb-8 p-6 rounded-lg w-full max-w-5xl mx-auto">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 divide-x divide-gray-200 text-center">
            <div class="flex flex-col items-center justify-center p-3">
                <div class="bg-blue-100 text-blue-500 p-3 rounded-xl mb-2">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <p class="text-xl font-semibold text-gray-800">{{ $totalRequest }}</p>
                <p class="text-sm text-gray-500">Usulan</p>
            </div>

            <div class="flex flex-col items-center justify-center p-3">
                <div class="bg-purple-100 text-purple-500 p-3 rounded-xl mb-2">
                    <i class="fa-solid fa-box text-xl"></i>
                </div>
                <p class="text-xl font-semibold text-gray-800">{{ $totalItems }}</p>
                <p class="text-sm text-gray-500">Barang</p>
            </div>

            <div class="flex flex-col items-center justify-center p-3">
                <div class="bg-green-100 text-green-500 p-3 rounded-xl mb-2">
                    <i class="fas fa-exchange-alt text-xl"></i>
                </div>
                <p class="text-xl font-semibold text-gray-800">{{ $totalMutation }}</p>
                <p class="text-sm text-gray-500">Mutasi</p>
            </div>

            <div class="flex flex-col items-center justify-center p-3">
                <div class="bg-amber-100 text-amber-500 p-3 rounded-xl mb-2">
                    <i class="fa-solid fa-wrench text-xl"></i>
                </div>
                <p class="text-xl font-semibold text-gray-800">{{ $totalMaintenance }}</p>
                <p class="text-sm text-gray-500">Pemeliharaan</p>
            </div>

            <div class="flex flex-col items-center justify-center p-3">
                <div class="bg-red-100 text-red-500 p-3 rounded-xl mb-2">
                    <i class="fa-solid fa-trash text-xl"></i>
                </div>
                <p class="text-xl font-semibold text-gray-800">{{ $totalRemove }}</p>
                <p class="text-sm text-gray-500">Penghapusan</p>
            </div>
        </div>
    </div>
    <div class="flex flex-wrap gap-6">
        <div class="bg-white shadow-lg rounded-lg p-6 inline-block justify-between">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-black">List Usulan Barang Baru</h2>
                <a href="{{ route('item-requests.index') }}" class="text-blue-400 hover:underline text-sm font-medium">Lihat
                    Semua</a>
            </div>
            <div class="w-full">
                <table class="w-full table-fixed text-center border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 font-medium border-b">Nama Barang</th>
                            <th class="p-2 font-medium border-b">Spesifikasi</th>
                            <th class="p-2 font-medium border-b">Jenis</th>
                            <th class="p-2 font-medium border-b">Qty</th>
                            <th class="p-2 font-medium border-b">Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($itemRequest as $itemRequest)
                            <tr class="hover:bg-amber-50">
                                <td class="p-2 font-light border-b">{{ $itemRequest->name }}</td>
                                <td class="p-2 font-light border-b">{{ $itemRequest->detail }}</td>
                                <td class="p-2 font-light border-b">{{ $itemRequest->type->name }}</td>
                                <td class="p-2 font-light border-b">{{ $itemRequest->qty }}</td>
                                <td class="p-2 font-light border-b">{{ $itemRequest->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-gray-500 font-light italic">
                                    Tidak ada data saat ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-black">List Barang</h2>
                <a href="{{ route('items.index') }}" class="text-blue-400 hover:underline text-sm font-medium">Lihat
                    Semua</a>
            </div>
            <div class="w-full">
                <table class="w-full table-fixed text-center border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 font-medium border-b">Kode Barang</th>
                            <th class="p-2 font-medium border-b">NUP</th>
                            <th class="p-2 font-medium border-b">Nama Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item as $item)
                            <tr class="hover:bg-amber-50">
                                <td class="p-2 font-light border-b">{{ $item->code }}</td>
                                <td class="p-2 font-light border-b">{{ $item->order_number }}</td>
                                <td class="p-2 font-light border-b">{{ $item->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-gray-500 font-light italic">
                                    Tidak ada data saat ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
