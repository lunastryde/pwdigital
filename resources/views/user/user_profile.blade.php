<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
    <body class="bg-gray-50 min-h-screen">
        <header class="relative w-full h-25 bg-blue-600 flex items-center justify-center">

            <div class="absolute top-2 left-4 z-10">
                <img src="{{ asset('images/pdao_logo.png') }}" 
                    alt="PWD Office Logo" 
                    class="w-25 h-25 md:w-28 md:h-28 rounded-full border-4 border-white shadow-lg">
            </div>

            <div class="absolute bottom-0 w-full bg-gray-200 bg-opacity-90 text-center py-1">
                <h1 class="text-lg font-semibold text-gray-800">
                    PERSON WITH DISABILITY AFFAIRS OFFICE
                </h1>
            </div>
        </header>

        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <livewire:user-edit />
            </div>
        </main>

        @livewireScripts
    </body>
</html>