<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <title>Register</title>
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="relative w-full h-25 bg-blue-600 flex items-center justify-center">
        <div class="absolute top-2 left-4 z-10">
            <img src="{{ asset('images/pdao_logo.jpg') }}" 
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
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Create your account</h2>

            <form action="/register" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        placeholder="you@example.com"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autocomplete="email"
                    />
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        placeholder="Choose a username"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autocomplete="username"
                    />
                </div>

                <!-- Name fields -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input
                            id="first_name"
                            type="text"
                            name="first_name"
                            placeholder=""
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                            autocomplete="given-name"
                        />
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input
                            id="middle_name"
                            type="text"
                            name="middle_name"
                            placeholder="Optional"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            autocomplete="additional-name"
                        />
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input
                            id="last_name"
                            type="text"
                            name="last_name"
                            placeholder=""
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                            autocomplete="family-name"
                        />
                    </div>
                </div>

                <!-- Contact & Gender -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input
                            id="contact_no"
                            type="text"
                            name="contact_no"
                            placeholder="09XXXXXXXXX"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                            inputmode="numeric"
                        />
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select
                            id="sex"
                            name="sex"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Create a password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center rounded-md bg-blue-600 px-4 py-2.5 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Register
                </button>

                <p class="text-sm text-center text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Sign in</a></p>
            </form>
        </div>
    </main>
</body>
</html>