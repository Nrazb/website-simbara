@extends('layouts.guest')

@section('title', 'Login | SIMBARA')

@section('content')
    <div class="flex min-h-screen">
        <!-- Ini kiri yang biru -->
        <div class="w-1/2 bg-blue-900 text-white flex flex-col rounded-r-3xl">
            <div class="flex-1 flex flex-col z-10">
                <div class="flex items-center gap-3 px-4 md:px-6 py-4">
                    <img src="{{ asset('images/polindra.png') }}" alt="Logo" class="w-10 h-10 md:w-12 md:h-12" />
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-base md:text-lg leading-tight">SIMBARA</span>
                        <span class="font-bold text-base md:text-lg leading-tight">POLINDRA</span>
                    </div>
                    <div class="hidden md:block h-10 md:h-12 w-px bg-gray-400 mx-2 md:mx-3"></div>
                    <p class="hidden md:block text-xs md:text-sm text-white leading-snug">
                        Sistem Informasi Manajemen <br>
                        Barang Milik Negara
                    </p>
                </div>

                <div class="flex flex-col justify-end h-1/2 px-6 py-8">
                    <h1 class="text-4xl font-bold mb-2">Selamat Datang!</h1>
                    <p class="text-lg text-white leading-relaxed">
                        Masukan Email dan Password <br>
                        Untuk Melanjutkan
                    </p>
                </div>
            </div>
        </div>
        <!-- Ini sebelah kanan ya - Form login -->
        <div class="w-1/2 flex items-center justify-center">
            <div class="w-full max-w-md px-8">
                <h2 class="text-2xl font-semibold mb-1">Welcome 👋</h2>
                <p class="text-gray-500 mb-6">Please login here</p>

                {{-- General error message --}}
                @if ($errors->has('code'))
                    <div class="mb-4 text-red-600 text-sm">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                @if ($errors->has('password'))
                    <div class="mb-4 text-red-600 text-sm">
                        {{ $errors->first('password') }}
                    </div>
                @endif

                <form action="#" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                        <input type="text" name="code" id="code" required autofocus
                            class="w-full border border-blue-900 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                            value="{{ old('code') }}" placeholder="12345678">
                        @error('code')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4 relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full border border-blue-900 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="••••••••">
                        @error('password')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember" class="form-checkbox text-blue-900 rounded">
                            <span class="ml-2 text-sm text-gray-700">Remember Me</span>
                        </label>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-900 hover:bg-amber-400 text-white  py-2 rounded-lg transition duration-200">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
