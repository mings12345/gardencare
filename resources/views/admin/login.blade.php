<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | GardenCare</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Include your CSS file -->
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-green-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-center text-green-700 mb-6">Admin Login</h1>

            <!-- Display errors -->
            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-green-400"
                        required
                        autofocus
                    >
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-green-400"
                        required
                    >
                </div>

                <!-- Remember Me -->
                <div class="mb-4 flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="mr-2"
                    >
                    <label for="remember" class="text-sm text-gray-600">Remember Me</label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition"
                    >
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>