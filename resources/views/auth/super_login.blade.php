<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/pdao_logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <title>Login</title>
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="relative w-full h-25 bg-violet-600 flex items-center justify-center">
        <div class="absolute top-2 left-4 z-10">
            <img src="{{ asset('images/pdao_logo.png') }}" 
                alt="PWD Office Logo" 
                class="w-25 h-25 md:w-28 md:h-28 rounded-full border-4 border-white shadow-lg">
        </div>

        <div class="absolute bottom-0 w-full bg-gray-200 bg-opacity-90 text-center py-1">
            <h1 class="text-lg font-semibold text-gray-800">
                Person with Disability Affairs Office
            </h1>
        </div>
    </header>
    
    <main class="min-h-[calc(100vh-120px)] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/pdao_logo.png') }}" 
                     alt="PWD Office Logo" 
                     class="w-40 h-40 rounded-full border-4 border-white shadow-lg">
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">SUPER ADMIN</h2>

            <form action="{{ route('super-admin.login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        placeholder="Enter your username"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autocomplete="username"
                    />
                    @error('username')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autocomplete="current-password"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center rounded-md bg-violet-600 px-4 py-2.5 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Login
                </button>
            </form>
        </div>
    </main>
</body>
</html>