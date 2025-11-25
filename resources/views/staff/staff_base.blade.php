<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
    <body class="bg-gray-50 min-h-screen">
        <main class="py-6">
            <div class="max-w-screen-2xl mx-auto px-2 sm:px-4 lg:px-6">
                <livewire:staff-view />
            </div>
        </main>

        @livewireScripts
        @stack('scripts')
    </body>
</html>