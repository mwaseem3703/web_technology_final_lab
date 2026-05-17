<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Premium Management</title>
</head>
<x-app-layout>

    <div x-data="{ dark: localStorage.getItem('darkMode') === 'true' || true }" 
         x-init="$watch('dark', value => localStorage.setItem('darkMode', value))"
         :class="{ 'dark': dark }">
        
        <div class="min-h-screen bg-slate-50 dark:bg-[#0F172A] py-8 text-slate-800 dark:text-slate-200 font-sans flex flex-col transition-colors duration-300">
            <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8 w-full flex-grow">
                
                @php
                    // Fetching real data directly from the authenticated user's tasks
                    $user = auth()->user();
                    
                    // Stats
                    $total = $user->tasks()->count();
                    $completed = $user->tasks()->where('status', 'Completed')->count();
                    $pending = $user->tasks()->where('status', 'Pending')->count();
                    
                    // Priorities for the Donut Chart
                    $high = $user->tasks()->where('priority', 'High')->count();
                    $medium = $user->tasks()->where('priority', 'Medium')->count();
                    $low = $user->tasks()->where('priority', 'Low')->count();

                    // Recent Tasks for the Table
                    $recentTasks = $user->tasks()->latest()->take(5)->get();
                @endphp

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div class="flex items-center gap-4">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-wide">Task Analytics</h2>
                    </div>
                    
                    <div class="flex items-center gap-3 text-sm">
                        
                        <button @click="dark = !dark" type="button" class="bg-white dark:bg-[#1E293B] hover:bg-slate-100 dark:hover:bg-[#334155] p-2.5 rounded-md border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-yellow-400 transition shadow-sm">
                            <svg x-show="dark" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 a4.22 0 011.415 0l.708.708a1 1 0 01-1.414 1.414l-.708-.708a1 1 0 010-1.414zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-4.22 4.22a1 1 0 010 1.415l-.708.708a1 1 0 01-1.414-1.414l.708-.708a1 1 0 011.415 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-4.22a1 1 0 01-1.415 0l-.708-.708a1 1 0 011.414-1.414l.708.708a1 1 0 010 1.415zM4 10a1 1 0 01-1 1H2a1 1 0 110-2h1a1 1 0 011 1zm4.22-4.22a1 1 0 010-1.415l-.708-.708a1 1 0 011.414 1.414l-.708.708a1 1 0 01-1.415 0zM10 5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"></path></svg>
                            <svg x-show="!dark" style="display: none;" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        </button>

                        <a href="{{ route('tasks.index') }}" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-md text-white flex items-center gap-2 transition shadow-sm font-medium">
                            <svg viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                                <path d="M19 11h-5V6a1 1 0 0 0-2 0v5H7a1 1 0 0 0 0 2h5v5a1 1 0 0 0 2 0v-5h5a1 1 0 0 0 0-2z" fill="#FFFFFF" />
                            </svg>
                            Add Task
                        </a>

                        <button class="bg-white dark:bg-[#1E293B] hover:bg-slate-100 dark:hover:bg-[#334155] px-4 py-2 rounded-md border border-slate-200 dark:border-slate-700 text-indigo-600 dark:text-[#38BDF8] flex items-center gap-2 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filters
                        </button>

                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 px-4 py-2 rounded-md border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 flex items-center gap-2 transition shadow-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">
                    
                    <div class="col-span-1 lg:col-span-7 grid grid-cols-1 md:grid-cols-3 gap-5">
                        
                        <div class="flex flex-col gap-5">
                            <div class="bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm flex-1 transition-colors">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Total Tasks</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">{{ now()->format('j F Y') }}</p>
                                <div class="text-3xl font-black text-indigo-600 dark:text-[#4ADE80] text-center mt-2 flex items-center justify-center gap-2">
                                    {{ $total }}
                                </div>
                            </div>
                            <div class="bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm flex-1 transition-colors">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Pending Tasks</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">{{ now()->format('j F Y') }}</p>
                                <div class="text-3xl font-black text-orange-500 dark:text-[#FBBF24] text-center mt-2 flex items-center justify-center gap-2">
                                    {{ $pending }}
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm flex flex-col items-center justify-center text-center transition-colors">
                            <div class="w-full text-left">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Completion Rate</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-8">{{ now()->format('j F Y') }}</p>
                            </div>
                            <div class="text-7xl md:text-8xl font-black text-green-500 dark:text-[#86EFAC] tracking-tighter flex items-center gap-2">
                                {{ $total > 0 ? round(($completed / $total) * 100) : 0 }}%
                            </div>
                        </div>

                        <div class="flex flex-col gap-5">
                            <div class="bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm flex-1 transition-colors">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Completed</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">{{ now()->format('j F Y') }}</p>
                                <div class="text-3xl font-black text-slate-800 dark:text-white text-center mt-2 flex items-center justify-center gap-2">
                                    {{ $completed }}
                                </div>
                            </div>
                            <div class="bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm flex-1 transition-colors">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">High Priority</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Urgent Actions</p>
                                <div class="text-3xl font-black text-red-500 dark:text-red-400 text-center mt-2">
                                    {{ $high }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 lg:col-span-5 bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm flex flex-col transition-colors">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Task Status Overview</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Pending vs Completed</p>
                        <div class="flex-1 w-full relative min-h-[220px]">
                            <canvas id="statusBarChart"></canvas>
                        </div>
                    </div>

                </div>

                <div class="mb-5 bg-white dark:bg-[#1E2538] rounded-xl p-6 border border-slate-200 dark:border-slate-700/50 shadow-sm transition-colors"
                     x-data="{
                        loading: false,
                        analysisText: '{{ $user->ai_dashboard_analysis ? addslashes(str_replace(["\r", "\n"], '\n', $user->ai_dashboard_analysis)) : '' }}',
                        cachedIndicator: '{{ $user->ai_analysis_cached_at ? $user->ai_analysis_cached_at->diffForHumans() : '' }}',
                        
                        // BULLETPROOF PARSER: Safely reads data even if formatting keys fluctuate slightly
                        get analysisBlock() {
                            if (!this.analysisText) return '';
                            let cleanText = this.analysisText.replace(/\*\*/g, '');
                            let matches = this.analysisText.match(/\*\*ANALYSIS:\*\*([\s\S]*?)(?=\*\*IMPROVEMENT|$)/i);
                            if (matches) return matches[1].trim();
                            
                            // Fallback if formatting doesn't match perfectly split down the middle
                            if (cleanText.toLowerCase().includes('improvement')) {
                                return cleanText.split(/improvement/i)[0].trim();
                            }
                            return cleanText;
                        },
                        get improvementsBlock() {
                            if (!this.analysisText) return [];
                            let matches = this.analysisText.match(/\*\*IMPROVEMENT SUGGESTIONS:\*\*([\s\S]*)$/i);
                            let blockContent = matches ? matches[1].trim() : '';
                            
                            if (!blockContent && this.analysisText.toLowerCase().includes('improvement')) {
                                let parts = this.analysisText.split(/improvement suggestions:/i);
                                blockContent = parts[1] ? parts[1].trim() : '';
                            }
                            
                            if (!blockContent) return [];
                            
                            // Transform markdown asterisks cleanly into an iterative list array
                            return blockContent.split('\n')
                                                .map(line => line.replace(/^[\s*\-\d\.]+\s*/, '').trim())
                                                .filter(line => line.length > 0);
                        },

                        fetchDashboardAi(force = false) {
                            this.loading = true;
                            fetch('{{ route('dashboard.ai-analytics') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ force_refresh: force })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.analysis) {
                                    this.analysisText = data.analysis.replace(/\r/g, '');
                                    this.cachedIndicator = data.cached_at;
                                }
                                this.loading = false;
                            })
                            .catch(() => {
                                this.loading = false;
                            });
                        }
                     }">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 dark:border-slate-700/50 pb-4 mb-5 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg text-indigo-600 dark:text-indigo-400 text-xl font-bold">✨</div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                    Academia.ai Cognitive Workload Profile Evaluation
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Generates a holistic academic progress audit based on your structural metrics.
                                </p>
                            </div>
                        </div>
                        
                        <button @click="fetchDashboardAi(analysisText ? true : false)" type="button" :disabled="loading"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-black text-xs uppercase tracking-wide rounded-md shadow-sm transition whitespace-nowrap">
                            <span x-text="loading ? '🔄 Recalculating Metrics...' : (analysisText ? '🔄 Refresh Progress Analysis' : '✨ Generate Profile Evaluation')"></span>
                        </button>
                    </div>

                    <div>
                        <div x-show="loading" class="py-12 flex flex-col items-center justify-center gap-3 text-xs font-bold text-indigo-600 dark:text-indigo-400 animate-pulse">
                            <span class="text-2xl animate-spin">🔄</span>
                            <span>Compiling milestone data segments, connecting to Gemini AI Core Platform...</span>
                        </div>

                        <div x-show="!analysisText && !loading" class="py-10 text-center border-2 border-dashed border-slate-200 dark:border-slate-700/60 rounded-xl bg-slate-50/50 dark:bg-slate-900/10">
                            <h4 class="text-xs font-bold text-slate-600 dark:text-slate-400">No Performance Overview Data Logs Found</h4>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Trigger an evaluation above to review progress pacing suggestions maps.</p>
                        </div>

                        <div x-show="analysisText && !loading" x-cloak x-transition class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            
                            <div class="md:col-span-5 bg-gradient-to-b from-indigo-50/30 via-slate-50/20 to-transparent dark:from-indigo-950/10 dark:via-slate-900/10 p-5 rounded-xl border border-slate-200 dark:border-slate-700/60 flex flex-col justify-between transition-colors">
                                <div>
                                    <span class="px-2.5 py-1 text-[9px] font-black tracking-widest uppercase rounded bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400 border border-indigo-200/20">
                                        Pacing Evaluation Report
                                    </span>
                                    <p class="text-xs font-medium leading-relaxed text-slate-700 dark:text-slate-300 mt-4"
                                       x-text="analysisBlock">
                                    </p>
                                </div>
                                
                                <div class="mt-5 border-t border-slate-200/60 dark:border-slate-700/40 pt-2.5 text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">
                                    ⏰ Storage Sync: Cache Linked <span x-text="cachedIndicator"></span>
                                </div>
                            </div>

                            <div class="md:col-span-7 space-y-3">
                                <span class="text-[10px] font-black tracking-widest uppercase text-slate-400 dark:text-slate-500 block mb-1">
                                    🎯 High-Impact Action Roadmaps
                                </span>

                                <div class="grid grid-cols-1 gap-2.5">
                                    <template x-for="(tip, index) in improvementsBlock" :key="index">
                                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 hover:border-indigo-200 dark:hover:border-indigo-900/60 transition shadow-sm">
                                            <div class="w-5 h-5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span x-text="index + 1"></span>
                                            </div>
                                            <p class="text-xs text-slate-600 dark:text-slate-300 font-medium leading-relaxed" x-text="tip"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <template x-if="improvementsBlock.length === 0">
                                    <div class="p-4 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 whitespace-pre-line" x-text="analysisText.replace(/\*\*/g, '')">
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    
                    <div class="col-span-1 lg:col-span-7 bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm overflow-hidden flex flex-col transition-colors">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">My Recent Tasks</h3>
                            <a href="{{ route('tasks.index') }}" class="text-indigo-600 dark:text-[#38BDF8] text-xs hover:underline font-bold">View All &rarr;</a>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Your latest assignments</p>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm whitespace-nowrap">
                                <thead class="text-[10px] text-slate-500 dark:text-slate-400 font-black uppercase tracking-wider border-b border-slate-200 dark:border-slate-700/50">
                                    <tr>
                                        <th class="pb-3 pl-2">Task Title</th>
                                        <th class="pb-3">Deadline</th>
                                        <th class="pb-3 text-center">Priority</th>
                                        <th class="pb-3 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                    @forelse($recentTasks as $task)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                        <td class="py-4 pl-2">
                                            <div class="font-bold text-slate-800 dark:text-white {{ $task->status === 'Completed' ? 'line-through opacity-50' : '' }}">{{ $task->title }}</div>
                                        </td>
                                        <td class="py-4 text-slate-600 dark:text-slate-300 text-xs {{ $task->status === 'Completed' ? 'line-through opacity-50' : '' }}">
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y g:i A') }}
                                        </td>
                                        <td class="py-4 text-center">
                                            <span class="text-[10px] font-bold px-3 py-1 rounded 
                                                {{ $task->priority == 'High' ? 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400' : 
                                                  ($task->priority == 'Medium' ? 'bg-orange-100 text-orange-600 dark:bg-orange-500/20 dark:text-orange-400' : 
                                                  'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400') }}">
                                                {{ strtoupper($task->priority) }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right">
                                            @if($task->status === 'Completed')
                                                <span class="text-green-600 dark:text-green-400 text-xs font-bold flex items-center justify-end gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Completed</span>
                                            @else
                                                <span class="text-yellow-600 dark:text-yellow-400 text-xs font-bold flex items-center justify-end gap-1"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-500 dark:text-slate-400 text-sm">No tasks found. Create one to see it here!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-span-1 lg:col-span-5 bg-white dark:bg-[#1E2538] p-5 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm transition-colors">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Priority Distribution</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">High vs Medium vs Low</p>
                        
                        <div class="flex items-center justify-between mt-4">
                            <div class="relative w-48 h-48 flex-shrink-0">
                                <canvas id="priorityChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-xs text-slate-500 dark:text-slate-300">Total</span>
                                    <span class="text-5xl font-black text-slate-900 dark:text-white">{{ $total }}</span>
                                </div>
                            </div>

                            <div class="flex-1 ml-8 space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-200 font-medium">
                                        <span class="w-3 h-3 rounded-sm bg-red-500"></span> High Priority
                                    </div>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $high }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-200 font-medium">
                                        <span class="w-3 h-3 rounded-sm bg-orange-400"></span> Medium Priority
                                    </div>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $medium }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-200 font-medium">
                                        <span class="w-3 h-3 rounded-sm bg-blue-500"></span> Low Priority
                                    </div>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $low }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <footer class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700/50 flex flex-col md:flex-row items-center justify-between text-sm text-slate-500 dark:text-slate-400 pb-4">
                    <p>&copy; {{ date('Y') }} TaskFlow Management. All rights reserved.</p>
                    <div class="flex items-center gap-4 mt-4 md:mt-0 font-medium">
                        <a href="#" class="hover:text-slate-800 dark:hover:text-slate-200 transition">Privacy Policy</a>
                        <a href="#" class="hover:text-slate-800 dark:hover:text-slate-200 transition">Terms of Service</a>
                        <a href="#" class="hover:text-slate-800 dark:hover:text-slate-200 transition">Support</a>
                    </div>
                </footer>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const gridColor = 'rgba(148, 163, 184, 0.1)';
            const tickColor = '#94A3B8';

            // 1. Status Bar Chart (Pending vs Completed)
            const statusCtx = document.getElementById('statusBarChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: ['Pending Tasks', 'Completed Tasks'],
                    datasets: [{
                        label: 'Task Count',
                        data: [{{ $pending }}, {{ $completed }}],
                        backgroundColor: ['#FBBF24', '#4ADE80'], 
                        borderRadius: 4,
                        barPercentage: 0.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: tickColor, font: { size: 12, weight: 'bold' } },
                            grid: { color: gridColor, drawBorder: false }
                        },
                        x: {
                            ticks: { color: tickColor, font: { size: 12, weight: 'bold' } },
                            grid: { display: false, drawBorder: false }
                        }
                    }
                }
            });

            // 2. Priority Donut Chart (High, Medium, Low)
            const priorityCtx = document.getElementById('priorityChart').getContext('2d');
            new Chart(priorityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['High', 'Medium', 'Low'],
                    datasets: [{
                        data: [{{ $high }}, {{ $medium }}, {{ $low }}], 
                        backgroundColor: [
                            '#EF4444', 
                            '#FB923C', 
                            '#3B82F6'  
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) { return ' ' + context.label + ' Priority: ' + context.raw + ' Tasks'; }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>