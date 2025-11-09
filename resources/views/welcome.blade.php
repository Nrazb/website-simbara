@extends('layouts.guest')

@section('title', 'SIMBARA POLINDRA')

@section('content')
<div class="min-h-screen flex flex-col">
    <header class="flex justify-between items-center px-6 py-4 shadow-md">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/polindra.png') }}" alt="Logo" class="w-12 h-12">
            <div class="flex flex-col">
                <span class="font-bold text-lg leading-tight">SIMBARA</span>
                <span class="font-bold text-lg leading-tight">POLINDRA</span>
            </div>
            <div class="h-10 w-px bg-gray-400 mx-3"></div>
            <p class="text-sm text-gray-700">
                Sistem Informasi Manajemen <br>
                Barang Milik Negara
            </p>
        </div>

        <a href="/login"
           class="px-4 py-2 border border-blue-900 rounded hover:bg-amber-400 transition">
            MASUK
        </a>
    </header>
</div>
@endsection
