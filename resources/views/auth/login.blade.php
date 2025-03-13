<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | GardenCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-200 to-green-100">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-green-700 mb-6">Admin Login</h1>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-md">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input 
                    id="email"
                    type="email"
                    name="email"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-green-400 focus:border-green-600 transition"
                    required autofocus
                >
            </div>

            <!-- Password Field with Eye Icon -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input 
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-green-400 focus:border-green-600 transition pr-10"
                        required
                    >
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-gray-500 focus:outline-none">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="remember" name="remember" class="mr-2">
                <label for="remember" class="text-sm text-gray-600">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition duration-200 ease-in-out shadow-md"
            >
                Log in
            </button>
        </form>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

</body>
</html>
