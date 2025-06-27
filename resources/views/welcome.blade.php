<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Leadify</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])



</head>
<!-- <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>                
            @endif           
        </div>
        
    </body> -->

<body class="bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen antialiased">
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('Leadify-Logo.png') }}" alt="Logo" class="h-16">
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input id="email" type="email" name="email" required autofocus
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none  dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none  dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between mb-4">
                    <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded focus:outline-none ">
                        Log in
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <div class="text-center mt-4">
                @if (Route::has('register'))
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account?
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-red-500 hover:underline">Register here</a>
                    @endif
                </p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>