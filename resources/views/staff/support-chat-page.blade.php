<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/pdao_logo.png') }}">
    <title>Staff Support Chat - PDAO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <main class="py-6">
        <div class="max-w-screen-2xl mx-auto px-2 sm:px-4 lg:px-6">
            <livewire:staff-support-chat />
        </div>
    </main>

    @livewireScripts
</body>
</html>
