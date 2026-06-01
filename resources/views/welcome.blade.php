<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTask Pro | M. Waseem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Continuous Marquee Animation */
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-250px * 5)); }
        }
        .animate-scroll {
            animation: scroll 30s linear infinite;
        }
        /* Subtle float animation for images */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased relative overflow-x-hidden text-slate-800">
    
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] rounded-full bg-blue-400/20 blur-[120px]"></div>
        <div class="absolute top-[10%] -right-[10%] w-[60%] h-[60%] rounded-full bg-indigo-400/20 blur-[120px]"></div>
        <div class="absolute bottom-[10%] left-[20%] w-[50%] h-[50%] rounded-full bg-purple-400/10 blur-[120px]"></div>
    </div>

    <nav class="flex items-center justify-between p-6 px-4 md:px-12 bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-white/40 shadow-sm">
        <div class="text-2xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent tracking-tighter">
            CampusTask.
        </div>
        <div class="hidden md:flex items-center gap-6">
            <a href="{{ route('admin.login') }}" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                Admin Portal
            </a>
            <div class="h-6 w-px bg-slate-200"></div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-blue-600 font-medium transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 font-medium hover:text-blue-600 transition">Log in</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-slate-900 text-white rounded-full font-semibold shadow-lg hover:bg-blue-600 transition transform hover:-translate-y-0.5">Sign Up</a>
                @endauth
            @endif
        </div>
    </nav>

    <header class="pt-32 pb-20 px-6 text-center max-w-5xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-sm font-semibold mb-8 shadow-sm">
            <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
            v2.0 Now Live for Spring Semester
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold text-slate-900 mb-8 tracking-tight leading-[1.1]">
            The Smartest Way to <br>
            <span class="bg-gradient-to-r from-blue-600 to-indigo-500 bg-clip-text text-transparent">Manage Campus Tasks.</span>
        </h1>
        <p class="text-xl md:text-2xl text-slate-600 max-w-3xl mx-auto mb-12 leading-relaxed font-light">
            Built specifically for high-performance software engineering students. Organize assignments, track progress, and collaborate in a secure environment.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-4 bg-blue-600 text-white rounded-2xl text-lg font-bold shadow-xl shadow-blue-600/30 hover:bg-blue-700 transition transform hover:-translate-y-1">Get Started for Free</a>
        </div>
    </header>

    <section class="py-12 border-y border-slate-200/60 bg-white/40 backdrop-blur-md overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center mb-8">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Trusted by students across leading campuses</p>
        </div>
        <div class="relative w-full overflow-hidden">
            <div class="flex animate-scroll w-[calc(250px*10)] opacity-60 grayscale hover:grayscale-0 transition duration-500">
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">COMSATS</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">FAST NUCES</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">NUST</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">UET</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">PUCIT</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">COMSATS</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">FAST NUCES</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">NUST</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">UET</h2></div>
                <div class="w-[250px] flex justify-center"><h2 class="text-3xl font-black text-slate-800 tracking-tighter">PUCIT</h2></div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto py-24 px-6">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 text-blue-600 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <h2 class="text-4xl font-extrabold text-slate-900 mb-6 leading-tight">Everything you need in one centralized dashboard.</h2>
                <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                    Stop jumping between group chats, email threads, and messy spreadsheets. CampusTask Pro brings your deadlines, lecture notes, and study plans into a single, beautiful workspace.
                </p>
                <ul class="space-y-4 text-slate-700 font-medium">
                    <li class="flex items-center gap-3"><svg class="text-blue-500 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Instant Live Search Engine</li>
                    <li class="flex items-center gap-3"><svg class="text-blue-500 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Priority-based Color Coding</li>
                    <li class="flex items-center gap-3"><svg class="text-blue-500 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Secure File Attachments</li>
                </ul>
            </div>
            
            <div class="relative animate-float">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-500 to-indigo-500 rounded-3xl transform rotate-3 opacity-20 blur-lg"></div>
                <div class="bg-white/80 p-2 rounded-3xl border border-white shadow-2xl relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1000&auto=format&fit=crop" alt="Dashboard Interface" class="rounded-2xl w-full object-cover h-[400px]">
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto py-24 px-6">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            
            <div class="relative order-2 md:order-1 animate-float" style="animation-delay: 2s;">
                <div class="absolute inset-0 bg-gradient-to-tr from-purple-500 to-pink-500 rounded-3xl transform -rotate-3 opacity-20 blur-lg"></div>
                <div class="bg-white/80 p-2 rounded-3xl border border-white shadow-2xl relative overflow-hidden w-full md:w-5/6 mx-auto">
                    <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=1000&auto=format&fit=crop" alt="AI Code Interface" class="rounded-2xl w-full object-cover h-[450px]">
                </div>
            </div>

            <div class="order-1 md:order-2">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 text-purple-600 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <h2 class="text-4xl font-extrabold text-slate-900 mb-6 leading-tight">AI-Powered Study Guidance.</h2>
                <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                    Powered by the Gemini 2.5 engine, our system automatically analyzes your task descriptions and generates concise study plans, time estimates, and targeted tips to secure top grades.
                </p>
                <a href="#how-it-works" class="font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-2">
                    Learn how AI caching works <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <section class="py-24 bg-slate-900 text-white relative overflow-hidden" 
             x-data="{ activeSlide: 0, slides: [0, 1, 2], autoInterval: null }" 
             x-init="autoInterval = setInterval(() => { activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1 }, 10000)">
        
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-blue-600/20 rounded-full blur-[150px] transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center min-h-[350px] flex flex-col justify-center">
            <svg class="w-14 h-14 mx-auto text-blue-500 mb-8 opacity-60" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
            
            <div x-show="activeSlide === 0" x-transition.opacity.duration.700ms class="absolute inset-0 flex flex-col justify-center items-center px-6">
                <h2 class="text-3xl md:text-4xl font-bold mb-10 leading-tight">
                    "CampusTask completely transformed how I handle my Software Engineering projects. The AI insights alone saved me hours of planning."
                </h2>
                <div class="flex items-center justify-center gap-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Ali Hassan" class="w-14 h-14 rounded-full border-2 border-blue-500 object-cover shadow-lg">
                    <div class="text-left">
                        <p class="font-bold text-lg text-white">Ali Hassan</p>
                        <p class="text-blue-400 text-sm font-medium">BSE Student, Batch SP24</p>
                    </div>
                </div>
            </div>

            <div x-show="activeSlide === 1" x-transition.opacity.duration.700ms class="absolute inset-0 flex flex-col justify-center items-center px-6" x-cloak>
                <h2 class="text-3xl md:text-4xl font-bold mb-10 leading-tight">
                    "The priority tracking is a lifesaver. Before this, I was losing track of lab assignments. Now, my entire semester is color-coded and organized."
                </h2>
                <div class="flex items-center justify-center gap-4">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah Khan" class="w-14 h-14 rounded-full border-2 border-indigo-500 object-cover shadow-lg">
                    <div class="text-left">
                        <p class="font-bold text-lg text-white">Sarah Khan</p>
                        <p class="text-indigo-400 text-sm font-medium">CS Senior, FAST NUCES</p>
                    </div>
                </div>
            </div>

            <div x-show="activeSlide === 2" x-transition.opacity.duration.700ms class="absolute inset-0 flex flex-col justify-center items-center px-6" x-cloak>
                <h2 class="text-3xl md:text-4xl font-bold mb-10 leading-tight">
                    "I love the clean UI and how fast the live search works. It feels incredibly premium compared to the standard university portals we usually use."
                </h2>
                <div class="flex items-center justify-center gap-4">
                    <img src="https://randomuser.me/api/portraits/men/46.jpg" alt="Omer Tariq" class="w-14 h-14 rounded-full border-2 border-purple-500 object-cover shadow-lg">
                    <div class="text-left">
                        <p class="font-bold text-lg text-white">Omer Tariq</p>
                        <p class="text-purple-400 text-sm font-medium">Software Developer</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 flex justify-center gap-3 mt-12">
            <button @click="activeSlide = 0; clearInterval(autoInterval)" :class="{'bg-blue-500 w-8': activeSlide === 0, 'bg-slate-600 w-3': activeSlide !== 0}" class="h-3 rounded-full transition-all duration-300"></button>
            <button @click="activeSlide = 1; clearInterval(autoInterval)" :class="{'bg-indigo-500 w-8': activeSlide === 1, 'bg-slate-600 w-3': activeSlide !== 1}" class="h-3 rounded-full transition-all duration-300"></button>
            <button @click="activeSlide = 2; clearInterval(autoInterval)" :class="{'bg-purple-500 w-8': activeSlide === 2, 'bg-slate-600 w-3': activeSlide !== 2}" class="h-3 rounded-full transition-all duration-300"></button>
        </div>
    </section>

    <section class="max-w-4xl mx-auto py-24 px-6" x-data="{ active: null }">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-slate-500">Everything you need to know about the platform.</p>
        </div>
        
        <div class="space-y-4">
            <div class="border border-slate-200 rounded-2xl bg-white shadow-sm overflow-hidden transition-all duration-200">
                <button @click="active = active === 1 ? null : 1" class="w-full px-6 py-5 text-left font-bold text-lg flex justify-between items-center focus:outline-none">
                    Is this platform free for students?
                    <svg class="w-5 h-5 transform transition-transform duration-200 text-slate-400" :class="{ 'rotate-180': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 1" x-collapse x-cloak class="px-6 pb-5 text-slate-600">
                    Yes! The core task management features are completely free for all active university students.
                </div>
            </div>

            <div class="border border-slate-200 rounded-2xl bg-white shadow-sm overflow-hidden transition-all duration-200">
                <button @click="active = active === 2 ? null : 2" class="w-full px-6 py-5 text-left font-bold text-lg flex justify-between items-center focus:outline-none">
                    How does the AI Study Guidance work?
                    <svg class="w-5 h-5 transform transition-transform duration-200 text-slate-400" :class="{ 'rotate-180': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 2" x-collapse x-cloak class="px-6 pb-5 text-slate-600">
                    We securely pass your task title and description to the Gemini API, which returns a customized, structured study plan and time estimate tailored to your specific coursework.
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 pt-16 pb-8 px-6 border-t border-slate-800 text-slate-400">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="md:col-span-2">
                <div class="text-2xl font-black text-white tracking-tighter mb-4">
                    CampusTask.
                </div>
                <p class="max-w-xs mb-6 leading-relaxed">
                    The premier task management and AI analysis suite for software engineering students.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-400 hover:text-white transition"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg></a>
                </div>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Product</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-blue-400 transition">Features</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Updates & Logs</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-blue-400 transition">Privacy Policy</a></li>
                    <li><a href="{{ route('admin.login') }}" class="hover:text-indigo-400 transition flex items-center gap-2">Admin Login <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg></a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto border-t border-slate-800 pt-8 text-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <div>© 2026 CampusTask Pro. Designed by M. Waseem.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>