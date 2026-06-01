<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | CampusTask</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for Password Toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex flex-col items-center justify-between p-6">

    <!-- Spacer for vertical centering -->
    <div class="w-full"></div>

    <div class="w-full max-w-lg my-auto">
        <!-- Brand Logo -->
        <div class="mb-12 flex items-center gap-2">
            <div class="h-8 w-1 bg-blue-600"></div>
            <span class="text-xl font-bold tracking-tight text-slate-800 uppercase">CampusTask.</span>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-12">
            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-semibold text-slate-900 mb-2">Sign in</h1>
                <p class="text-slate-500 text-sm">Sign in to continue to your dashboard</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-8">
                @csrf

                <!-- Email Field -->
                <div class="relative">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus
                        class="w-full border-b-2 border-slate-200 py-3 focus:outline-none focus:border-blue-600 transition-colors bg-transparent placeholder-slate-400 text-slate-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password Field with Toggle -->
                <div class="relative" x-data="{ show: false }">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" placeholder="Password" required 
                        class="w-full border-b-2 border-slate-200 py-3 pr-10 focus:outline-none focus:border-blue-600 transition-colors bg-transparent placeholder-slate-400 text-slate-700">
                    
                    <!-- Toggle Button -->
                    <button type="button" @click="show = !show" class="absolute right-2 top-4 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                        <!-- Eye Icon (Show) -->
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <!-- Eye Off Icon (Hide) -->
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                    </button>
                    
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="remember_me" class="text-slate-600 text-sm font-medium">Remember me</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 font-medium hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-[#1D75D3] text-white py-4 rounded-lg font-medium text-lg hover:bg-blue-700 shadow-md transition-all active:scale-[0.98]">
                    Sign in
                </button>
            </form>
        </div>

        <!-- Sign Up Link -->
        <div class="text-center mt-8">
            <p class="text-slate-500 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Sign up</a>
            </p>
        </div>
    </div>

    <!-- Simple Footer -->
    <footer class="w-full text-center mt-12 text-slate-400 text-sm">
        <p>&copy; {{ date('Y') }} CampusTask. All rights reserved.</p>
    </footer>

</body>
</html>