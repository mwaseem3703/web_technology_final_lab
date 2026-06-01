<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | CampusTask</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <div class="w-full max-w-lg my-auto" x-data="{ 
        password: '', 
        name: '',
        show: false,
        showConfirm: false,
        get strength() {
            if (this.password.length === 0) return 0;
            let s = 0;
            if (this.password.length >= 8) s++;
            if (/[!@#$%^&*(),.?':{}|<>]/.test(this.password)) s++;
            return s;
        }
    }">
        <!-- Brand Logo -->
        <div class="mb-12 flex items-center gap-2">
            <div class="h-8 w-1 bg-blue-600"></div>
            <span class="text-xl font-bold tracking-tight text-slate-800 uppercase">CampusTask.</span>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-12">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-semibold text-slate-900 mb-2">Sign up</h1>
                <p class="text-slate-500 text-sm">Create your account to start managing tasks</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                <!-- Name Field -->
                <div class="relative">
                    <input id="name" type="text" name="name" placeholder="Full Name" required 
                        x-model="name"
                        @input="name = name.replace(/[0-9]/g, '')"
                        class="w-full border-b-2 border-slate-200 py-3 focus:outline-none focus:border-blue-600 transition-colors bg-transparent placeholder-slate-400 text-slate-700">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Field -->
                <div class="relative">
                    <input id="email" type="email" name="email" placeholder="Email" required 
                        class="w-full border-b-2 border-slate-200 py-3 focus:outline-none focus:border-blue-600 transition-colors bg-transparent placeholder-slate-400 text-slate-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password Field with Strength Meter & Toggle -->
                <div class="relative">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" placeholder="Password" required 
                        x-model="password"
                        class="w-full border-b-2 border-slate-200 py-3 pr-10 focus:outline-none focus:border-blue-600 transition-colors bg-transparent placeholder-slate-400 text-slate-700">
                    
                    <!-- Toggle Button -->
                    <button type="button" @click="show = !show" class="absolute right-2 top-4 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                    </button>

                    <!-- Dynamic Strength Bar -->
                    <div class="mt-3 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-500"
                            :class="{
                                'w-0': strength === 0,
                                'w-1/2 bg-red-500': strength === 1,
                                'w-full bg-green-500': strength === 2
                            }">
                        </div>
                    </div>
                    <div class="mt-2 flex flex-col gap-1 text-[11px]">
                        <span :class="password.length >= 8 ? 'text-green-600 font-bold' : 'text-slate-400'">
                            ● Min 8 characters
                        </span>
                        <span :class="/[!@#$%^&*(),.?':{}|<>]/.test(password) ? 'text-green-600 font-bold' : 'text-slate-400'">
                            ● At least 1 special character
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password with Toggle -->
                <div class="relative">
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" placeholder="Confirm Password" required 
                        class="w-full border-b-2 border-slate-200 py-3 pr-10 focus:outline-none focus:border-blue-600 transition-colors bg-transparent placeholder-slate-400 text-slate-700">
                    
                    <!-- Toggle Button -->
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute right-2 top-4 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                        <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg x-show="showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                    </button>
                </div>

                <button type="submit" 
                    class="w-full bg-[#1D75D3] text-white py-4 rounded-lg font-medium text-lg hover:bg-blue-700 shadow-md transition-all active:scale-[0.98]">
                    Create Account
                </button>

                <div class="relative flex py-5 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-4 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Access Quickly</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <button type="button" class="border border-slate-200 py-3 rounded-lg text-blue-600 text-xs font-bold hover:bg-slate-50 transition">Google</button>
                    <button type="button" class="border border-slate-200 py-3 rounded-lg text-blue-600 text-xs font-bold hover:bg-slate-50 transition">Linkedin</button>
                    <button type="button" class="border border-slate-200 py-3 rounded-lg text-blue-600 text-xs font-bold hover:bg-slate-50 transition">SSO</button>
                </div>
            </form>
        </div>

        <p class="text-center mt-8 text-slate-500 text-sm">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Sign in</a>
        </p>
    </div>

    <!-- Simple Footer -->
    <footer class="w-full text-center mt-12 text-slate-400 text-sm">
        <p>&copy; {{ date('Y') }} CampusTask. All rights reserved.</p>
    </footer>

</body>
</html>