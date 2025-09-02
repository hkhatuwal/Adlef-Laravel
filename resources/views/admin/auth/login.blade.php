<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Dashboard</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @include('admin._partials.styles')

</head>
<body class="bg-gradient-to-br from-slate-800 to-slate-900 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Admin Badge -->
        <div class="fixed top-0 w-full bg-red-600 text-white text-center py-2 font-semibold tracking-wide shadow-lg">
            <i class="fas fa-shield-alt mr-2"></i> ADMIN CONTROL PANEL
        </div>

        <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8 relative">
            <!-- Admin Icon -->
            <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                <div class="bg-slate-900 rounded-full p-4 shadow-lg">
                    <i class="fas fa-user-shield text-3xl text-white"></i>
                </div>
            </div>

            <!-- Header -->
            <div class="text-center pt-6">
                <img class="mx-auto h-16 w-auto mb-4" src="{{asset('assets/images/logo.svg')}}" alt="Logo">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    Administrator Access
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Secure login portal for authorized personnel only
                </p>
            </div>

            <!-- Login Form -->
            <form class="mt-8 space-y-6" action="{{route('admin.login')}}" method="post">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">
                        <i class="fas fa-envelope mr-2 text-slate-400"></i>Admin Email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" value="{{config('app.debug')?'himtech727@gmail.com':''}}"  required
                            class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-lg
                            placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500
                            transition duration-150 ease-in-out"
                            placeholder="admin@example.com">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">
                        <i class="fas fa-lock  mr-2 text-slate-400"></i>Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" value="{{config('app.debug')?'password':''}}"  required
                            class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-lg
                            placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500
                            transition duration-150 ease-in-out"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-slate-300 rounded
                        transition duration-150 ease-in-out">
                    <label for="remember" class="ml-2 block text-sm text-slate-700">
                        Keep me signed in
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent
                        text-sm font-semibold rounded-lg text-white bg-red-600 hover:bg-red-700
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                        transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-red-300 group-hover:text-red-200
                            transition duration-150 ease-in-out"></i>
                        </span>
                        Sign in to Dashboard
                    </button>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 text-center text-xs text-slate-600 border-t border-slate-200 pt-4">
                <i class="fas fa-shield-alt text-red-500 mr-1"></i>
                This is a secure area. Unauthorized access is prohibited.
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-slate-400">
            <i class="fas fa-clock mr-1"></i>
            {{ now()->format('Y') }} &copy; All rights reserved
        </div>
    </div>
</body>
@include('admin._partials.scripts')

</html>
