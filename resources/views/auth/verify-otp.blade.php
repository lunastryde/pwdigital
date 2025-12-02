<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/pdao_logo.png') }}">
    <title>Verify Email - PDAO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans">

    {{-- Main Card Container --}}
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        
        {{-- Header Section --}}
        <div class="mb-8 text-center sm:text-left">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Verify Email</h2>
            <p class="text-sm text-gray-500">
                We’ve sent a code to <span class="font-medium text-gray-900">{{ $email }}</span>
            </p>
        </div>

        {{-- Alerts (Success/Error) --}}
        @if (session('status'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Main Verification Form --}}
        <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    OTP Code
                </label>
                <input
                    type="text"
                    name="code"
                    maxlength="6"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200 text-center tracking-[0.5em] text-lg text-gray-800 placeholder-gray-400"
                    placeholder="......"
                    value="{{ old('code') }}"
                    autofocus
                >
            </div>

            <button
                type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Verify
            </button>
        </form>

        {{-- Resend Link (Styled to match the "Not registered?" text in image) --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Didn't receive the code?
                <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline bg-transparent border-none cursor-pointer p-0 transition">
                        Resend
                    </button>
                </form>
            </p>
        </div>

    </div>

</body>
</html>