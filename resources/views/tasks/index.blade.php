<!DOCTYPE html>
<html lang="en" x-data="taskManager()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskflow | Premium Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 font-sans min-h-screen flex flex-col transition-colors duration-300">

    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 py-6 mb-10 shadow-sm transition-colors">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">TaskFlow <span class="text-gray-400 dark:text-gray-500 font-light">| Management</span></h1>
            
            <div class="flex items-center gap-4">
                <div class="text-sm font-medium text-gray-600 dark:text-gray-300 hidden md:block">
                    Welcome, {{ auth()->user()->name ?? 'User' }}
                </div>
                
                <button @click="darkMode = !darkMode" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition shadow-sm">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>

                <a href="{{ route('dashboard') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-5 py-2 rounded-xl text-sm font-bold transition shadow-sm border border-gray-200 dark:border-gray-600">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 text-red-600 dark:text-red-400 px-5 py-2 rounded-xl text-sm font-bold transition shadow-sm border border-red-200 dark:border-red-500/30">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 flex-grow w-full">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="relative w-full md:w-96">
                <input type="text" x-model="search" placeholder="Search tasks instantly..." 
                    class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 dark:text-white rounded-xl py-3 px-12 outline-none focus:ring-2 ring-indigo-500 transition shadow-sm placeholder-gray-400 dark:placeholder-gray-500">
                <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <button @click="openCreateModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg whitespace-nowrap">
                + Add New Task
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[800px]">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider">
                        <tr>
                            <th class="p-5">Task Details & AI Coaching</th>
                            <th class="p-5">Deadline Remaining</th>
                            <th class="p-5 text-center">Priority</th>
                            <th class="p-5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($tasks as $task)
                        <tr x-show="matchesSearch('{{ addslashes($task->title) }}', '{{ addslashes($task->description) }}')" class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                            <td class="p-5 align-top">
                                <div class="font-bold text-gray-900 dark:text-gray-100 text-lg {{ $task->status === 'Completed' ? 'line-through opacity-50' : '' }}">{{ $task->title }}</div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 {{ $task->status === 'Completed' ? 'line-through opacity-50' : '' }}">{{ $task->description ?? 'No description provided.' }}</p>

                                <div class="mt-3 flex flex-wrap items-center gap-2" x-data="{ 
                                    openAi: false, 
                                    loading: false, 
                                    loaded: false,
                                    aiData: { time_estimate: '', study_plan: '', study_tips: '' },
                                    getGuidance() {
                                        if (this.openAi) {
                                            this.openAi = false;
                                            return;
                                        }
                                        this.openAi = true;
                                        if (!this.loaded) {
                                            this.loading = true;
                                            fetch(`/tasks/{{ $task->id }}/ai-guidance`)
                                                .then(res => res.json())
                                                .then(data => {
                                                    this.aiData = data;
                                                    this.loading = false;
                                                    this.loaded = true;
                                                })
                                                .catch(() => {
                                                    this.loading = false;
                                                    this.aiData = { 
                                                        time_estimate: '3-4 hours', 
                                                        study_plan: 'Error matching context variables across backend network paths.', 
                                                        study_tips: 'Verify database structural allocations.' 
                                                    };
                                                });
                                        }
                                    }
                                }">
                                    <button @click="getGuidance()" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 font-bold text-xs transition border border-indigo-100 dark:border-indigo-500/20 shadow-sm">
                                        <span x-text="openAi ? '✨ Hide Guide' : '✨ Get AI Guidance'"></span>
                                    </button>

                                   @if($task->file_path)
<a href="{{ route('tasks.view-material', $task) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold text-xs transition border border-emerald-100 dark:border-emerald-500/20 shadow-sm">
    📁 View Material Live
</a>
@endif

                                    <div x-show="openAi" x-cloak x-transition class="w-full mt-4 p-4 bg-gradient-to-r from-blue-50/80 to-indigo-50/50 dark:from-blue-950/20 dark:to-indigo-950/20 rounded-xl border border-blue-100 dark:border-blue-900/30">
                                        <p class="text-[10px] font-black text-blue-700 dark:text-blue-400 uppercase tracking-wider mb-2">
                                            Academia.ai Copilot Real-Time Diagnostics
                                        </p>
                                        
                                        <div x-show="loading" class="py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 animate-pulse flex items-center justify-center gap-2">
                                            🔄 Processing variables, compiling metrics data...
                                        </div>

                                        <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-600 dark:text-gray-300">
                                            <div class="space-y-2">
                                                <p><strong>⏱️ Estimated Effort:</strong> <span x-text="aiData.time_estimate"></span></p>
                                                <p><strong>📋 Action Roadmap:</strong> <span x-text="aiData.study_plan"></span></p>
                                            </div>
                                            <div class="bg-white/80 dark:bg-gray-900/60 p-3 rounded-lg border border-indigo-100 dark:border-indigo-900/40">
                                                <p class="font-bold text-indigo-700 dark:text-indigo-400 mb-1">🎯 Grade Optimization Strategies:</p>
                                                <p class="italic leading-relaxed text-gray-700 dark:text-gray-300" x-text="aiData.study_tips"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 align-top">
                                <div class="text-sm font-semibold text-indigo-600 dark:text-indigo-400" x-data="timer('{{ \Carbon\Carbon::parse($task->due_date)->toIso8601String() }}')" x-init="start()">
                                    <span x-text="timeLeft"></span>
                                </div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Ends: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</div>
                            </td>
                            <td class="p-5 text-center align-top">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                    {{ $task->priority == 'High' ? 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400' : ($task->priority == 'Medium' ? 'bg-orange-100 text-orange-600 dark:bg-orange-500/20 dark:text-orange-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400') }}">
                                    {{ $task->priority }}
                                </span>
                            </td>
                            <td class="p-5 align-top">
                                <div class="flex justify-center gap-4">
                                    <button @click="openEditModal({{ json_encode($task) }})" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium text-sm">Edit</button>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 dark:text-red-400 hover:underline font-medium text-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-16 text-center text-gray-500 dark:text-gray-400">
                                No tasks found. Click "Add New Task" to get started!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 mb-10">
            {{ $tasks->links() }}
        </div>
    </main>

    <footer class="mt-auto py-8 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-center text-gray-400 dark:text-gray-500 text-sm transition-colors">
        &copy; {{ date('Y') }} TaskFlow Management. Built by {{ auth()->user()->name ?? 'M. Waseem' }}.
    </footer>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-3xl p-8 shadow-2xl overflow-hidden transition-colors" @click.away="showModal = false">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white" x-text="isEdit ? 'Update Task Workspace' : 'Add New Task Resource'"></h2>
            
            <form :action="isEdit ? `/tasks/${currentTask.id}` : '/tasks'" method="POST" enctype="multipart/form-data">
                @csrf
                <template x-if="isEdit"><input type="hidden" name="_method" value="PATCH"></template>

                <input type="hidden" name="remove_file" :value="removeFileFlag">

                <div class="grid grid-cols-1 gap-5">
                    <input type="text" name="title" x-model="currentTask.title" placeholder="Task Title" class="w-full bg-transparent border border-gray-300 dark:border-gray-600 dark:text-white rounded-xl p-3 outline-none focus:ring-2 ring-indigo-500 placeholder-gray-400 dark:placeholder-gray-500" required>
                    
                    <textarea name="description" x-model="currentTask.description" placeholder="Task details and description..." class="w-full bg-transparent border border-gray-300 dark:border-gray-600 dark:text-white rounded-xl p-3 outline-none focus:ring-2 ring-indigo-500 placeholder-gray-400 dark:placeholder-gray-500"></textarea>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400 ml-1">Start Date</label>
                            <input type="datetime-local" name="start_date" x-model="currentTask.start_date" class="w-full bg-transparent border border-gray-300 dark:border-gray-600 dark:text-white rounded-xl p-3 text-sm focus:ring-2 ring-indigo-500 outline-none style-color-scheme">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400 ml-1">Due Date</label>
                            <input type="datetime-local" name="due_date" x-model="currentTask.due_date" class="w-full bg-transparent border border-gray-300 dark:border-gray-600 dark:text-white rounded-xl p-3 text-sm focus:ring-2 ring-indigo-500 outline-none style-color-scheme" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400 ml-1">Priority</label>
                            <select name="priority" x-model="currentTask.priority" class="w-full bg-transparent border border-gray-300 dark:border-gray-600 dark:text-white rounded-xl p-3 text-sm focus:ring-2 ring-indigo-500 outline-none">
                                <option value="Low" class="dark:bg-gray-800 text-black dark:text-white">Low Priority</option>
                                <option value="Medium" class="dark:bg-gray-800 text-black dark:text-white">Medium Priority</option>
                                <option value="High" class="dark:bg-gray-800 text-black dark:text-white">High Priority</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400 ml-1">Status</label>
                            <select name="status" x-model="currentTask.status" class="w-full bg-transparent border border-gray-300 dark:border-gray-600 dark:text-white rounded-xl p-3 text-sm focus:ring-2 ring-indigo-500 outline-none">
                                <option value="Pending" class="dark:bg-gray-800 text-black dark:text-white">Pending</option>
                                <option value="Completed" class="dark:bg-gray-800 text-black dark:text-white">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="text-xs text-gray-500 dark:text-gray-400 ml-1">Helping Reference Document (Optional: .pdf, .docx, .pptx)</label>
                        <div class="mt-1.5 flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                <div class="flex flex-col items-center justify-center pt-3 pb-4">
                                    <svg class="w-6 h-6 mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Click to select file path</p>
                                </div>
                                <input type="file" name="attachment" class="hidden" @change="onFileSelected($event)" />
                            </label>
                        </div>

                        <template x-if="selectedFileName">
                            <div class="mt-2.5 flex items-center gap-2 p-2 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100/70 dark:border-indigo-900/30">
                                <span class="text-sm">📎</span>
                                <div class="text-xs truncate text-indigo-600 dark:text-indigo-400 font-semibold flex-grow">
                                    Selected: <span x-text="selectedFileName"></span> (<span x-text="selectedFileSize"></span>)
                                </div>
                                <button type="button" @click="clearFileSelection()" class="text-xs font-black text-gray-400 hover:text-red-500 px-1">✕</button>
                            </div>
                        </template>

                        <template x-if="isEdit && currentTask.file_path && !removeFileFlag">
                            <div class="mt-2.5 flex items-center justify-between p-2 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[70%]">📁 Has existing course attachment document</span>
                                <button type="button" @click="removeFileFlag = 1" class="text-[10px] font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 px-2.5 py-1 rounded-lg hover:bg-red-100 transition shadow-sm border border-red-200/20">
                                    Remove File
                                </button>
                            </div>
                        </template>
                        <template x-if="removeFileFlag">
                            <p class="text-[11px] text-red-500 font-medium mt-2">🛑 Existing document marked for deletion upon update execution.</p>
                        </template>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="button" @click="showModal = false" class="flex-1 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">Cancel</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition" x-text="isEdit ? 'Save Changes' : 'Create Task'"></button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .dark .style-color-scheme { color-scheme: dark; }
    </style>

    <script>
        function taskManager() {
            return {
                darkMode: localStorage.getItem('darkMode') === 'true',
                search: '',
                showModal: false,
                isEdit: false,
                removeFileFlag: 0,
                selectedFileName: '', // Live state variable for file name
                selectedFileSize: '', // Live state variable for file sizes metric
                currentTask: {},
                
                init() {
                    this.$watch('darkMode', value => {
                        localStorage.setItem('darkMode', value);
                    });
                },
                
                openCreateModal() {
                    this.isEdit = false;
                    this.removeFileFlag = 0;
                    this.selectedFileName = '';
                    this.selectedFileSize = '';
                    this.currentTask = { priority: 'Medium', status: 'Pending' };
                    this.showModal = true;
                },
                
                openEditModal(task) {
                    this.isEdit = true;
                    this.removeFileFlag = 0;
                    this.selectedFileName = '';
                    this.selectedFileSize = '';
                    task.start_date = task.start_date ? task.start_date.substring(0,16) : '';
                    task.due_date = task.due_date ? task.due_date.substring(0,16) : '';
                    this.currentTask = {...task};
                    this.showModal = true;
                },

                // FIX: Added live file metadata reader logic loops inside client engine
                onFileSelected(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFileName = file.name;
                        this.selectedFileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                        this.removeFileFlag = 0; // Uploading clears out pending removal queues
                    }
                },

                clearFileSelection() {
                    this.selectedFileName = '';
                    this.selectedFileSize = '';
                    const input = document.querySelector('input[type="file"]');
                    if (input) input.value = ''; // Hard drop file selection references from input node
                },

                matchesSearch(title, desc) {
                    if (!this.search) return true;
                    const q = this.search.toLowerCase();
                    return title.toLowerCase().includes(q) || desc.toLowerCase().includes(q);
                }
            }
        }

        function timer(expiry) {
            return {
                timeLeft: '',
                start() {
                    const update = () => {
                        const now = new Date().getTime();
                        const dist = new Date(expiry).getTime() - now;
                        if (dist < 0) return this.timeLeft = 'Expired';
                        
                        const d = Math.floor(dist / (1000 * 60 * 60 * 24));
                        const h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
                        const s = Math.floor((dist % (1000 * 60)) / 1000);
                        this.timeLeft = `${d}d ${h}h ${m}m ${s}s`;
                    };
                    update();
                    setInterval(update, 1000);
                }
            }
        }
    </script>
</body>
</html>