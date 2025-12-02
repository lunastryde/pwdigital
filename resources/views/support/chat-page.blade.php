<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/pdao_logo.png') }}">
    <title>Support Chat - PDAO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">

    <main class="py-6">
        <div class="max-w-5xl mx-auto px-4">
            {{-- You can add your navbar/header here if you want --}}
            <livewire:user-support-chat />
        </div>
    </main>

    @livewireScripts
</body>
</html>
