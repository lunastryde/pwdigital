<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/pdao_logo.png') }}">
    <title>Reset Password - PDAO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans">

    {{-- Main Card --}}
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        
        {{-- Header Section --}}
        <div class="mb-8 text-center sm:text-left">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
            <p class="text-sm text-gray-500">
                Please enter your new password below.
            </p>
        </div>

        {{-- Alerts --}}
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

        {{-- Form --}}
        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    New Password
                </label>
                <input
                    type="password"
                    name="password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200 text-gray-800 placeholder-gray-400"
                    placeholder="••••••••"
                    required
                    autofocus
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Confirm Password
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200 text-gray-800 placeholder-gray-400"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button
                type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Reset Password
            </button>
        </form>

        {{-- Footer Link --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold hover:underline transition">
                &larr; Back to Sign In
            </a>
        </div>

    </div>

</body>
</html>