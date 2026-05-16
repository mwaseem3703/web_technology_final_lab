<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTask Pro | M. Waseem</title>
   <script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="flex items-center justify-between p-6 px-12 bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
        <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
            CampusTask.
        </div>
        <div class="space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-blue-600 font-medium transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 text-slate-600 font-medium hover:text-blue-600 transition">Log in</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-full font-semibold shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-0.5">Sign Up</a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero -->
    <header class="py-24 px-6 text-center">
        <h1 class="text-6xl font-extrabold text-slate-900 mb-6 tracking-tight">
            The Smartest Way to <br>
            <span class="text-blue-600">Manage Campus Tasks.</span>
        </h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
            Built for students at COMSATS. Organize assignments, track progress, and collaborate in a secure environment.
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-4 bg-slate-900 text-white rounded-xl text-lg font-bold hover:bg-slate-800 transition">Get Started for Free</a>
        </div>
    </header>

    <!-- Feature Grid -->
    <section class="max-w-6xl mx-auto py-20 px-6 grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="text-3xl mb-4">🚀</div>
            <h3 class="font-bold text-xl mb-2">Live Search</h3>
            <p class="text-slate-500">Find any assignment instantly using our reactive Alpine.js engine.</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="text-3xl mb-4">🛡️</div>
            <h3 class="font-bold text-xl mb-2">Secure Auth</h3>
            <p class="text-slate-500">Your data is protected by Laravel Breeze's industry-standard security.</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="text-3xl mb-4">📈</div>
            <h3 class="font-bold text-xl mb-2">Priority Tracking</h3>
            <p class="text-slate-500">Stay focused on high-priority deadlines with color-coded alerts.</p>
        </div>
    </section>
</body>
</html>