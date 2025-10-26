@extends('layouts.app')

@section('title', 'Item Request | SIMBARA')

@section('content')
@include('item_requests.create')
@include('item_requests.edit')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Usulan Barang Baru</h2>
        <p class="font-light text-gray-400 text-sm">Catat dan kirim usulan barang yang dibutuhkan unit anda</p>
    </div>

    @include('components.header')
</div>
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <input type="text" placeholder="Search Item"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
        </div>

        <div class="flex items-center gap-2">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1" data-modal-target="create-usulan">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Usulan</span>
            </button>
            {{-- <button class="border border-gray-300 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1">
                <i class="fa-solid fa-upload"></i>
                <span>Export Data</span>
            </button> --}}
        </div>
    </div>

    <!-- Tampilan tabel di dekstop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full border-collapse text-sm text-left text-gray-600">
            <thead class="text-gray-700 bg-gray-100 font-semibold">
                <tr>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Spesifikasi</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">Tv</td>
                    <td class="px-4 py-3">2 inch</td>
                    <td class="px-4 py-3">Monitor</td>
                    <td class="px-4 py-3">10</td>
                    <td class="px-4 py-3">Buat nonton bro</td>
                    <td class="px-4 py-3">Upa Tik</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <button class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-lg">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg" data-modal-target="edit-usulan">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tampilan kartu untuk bentuk mobile-->
    <div class="block md:hidden space-y-3">
        <div class="border border-gray-200 rounded-xl p-3 shadow-sm">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-gray-800 text-lg">Tv</h3>
                <input type="checkbox" class="h-4 w-4">
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <p><span class="font-medium">Spesifikasi:</span> 2 inch</p>
                <p><span class="font-medium">Jenis:</span> Monitor</p>
                <p><span class="font-medium">Quantity:</span> 10</p>
                <p><span class="font-medium">Alasan:</span> Buat nonton bro</p>
                <p><span class="font-medium">Unit:</span> Upa Tik</p>
            </div>
            <div class="flex justify-end space-x-2 mt-3">
                <button class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-lg text-xs">
                    <i class="fa-regular fa-eye"></i>
                </button>
                <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg text-xs">
                    <i class="fa-solid fa-pencil"></i>
                </button>
                <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg text-xs" data-modal-target="delete-modal" >
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>


    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-xs sm:text-sm text-gray-500 gap-3">
        <div class="flex items-center space-x-2">
            <span>Showing</span>
            <select class="border border-blue-900 rounded-md text-gray-700 px-2 py-1 focus:ring-1 focus:ring-blue-500">
                <option>5</option>
                <option>10</option>
                <option>15</option>
            </select>
            <span>items</span>
        </div>

        <div class="flex space-x-1">
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">&lt;</button>
            <button class="px-2 sm:px-3 py-1 border border-blue-900 rounded-lg">1</button>
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">2</button>
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">3</button>
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">&gt;</button>
        </div>
    </div>
</div>


@endsection
