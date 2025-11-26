<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - PDAO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans">

    {{-- Main Card --}}
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        
        {{-- Header Section --}}
        <div class="mb-8 text-center sm:text-left">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password</h2>
            <p class="text-sm text-gray-500">
                Enter your email address and we'll send you a link to reset your password.
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
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email address
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition duration-200 text-gray-800 placeholder-gray-400"
                    placeholder="you@example.com"
                    required
                    autofocus
                >
            </div>

            <button
                type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Send Reset Link
            </button>
        </form>

        {{-- Back to Login Link --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Remember your password?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition">
                    Sign In
                </a>
            </p>
        </div>

    </div>

</body>
</html>