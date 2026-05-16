<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskflow | Premium Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #f3f4f6; }
    </style>
</head>
<body class="text-gray-800 font-sans" x-data="taskManager()">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 py-6 mb-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">TaskFlow <span class="text-gray-400 font-light">| Management</span></h1>
            <div class="text-sm text-gray-500">M. Waseem (BSE-SP24-053)</div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6">
        
        <!-- Controls: Search and Add -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="relative w-full md:w-96">
                <input type="text" x-model="search" placeholder="Search tasks instantly..." 
                    class="w-full bg-white border border-gray-300 rounded-xl py-3 px-12 outline-none focus:ring-2 ring-indigo-500 transition shadow-sm">
                <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <button @click="openCreateModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg">
                + Add New Task
            </button>
        </div>

        <!-- Task Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="p-5">Task & Description</th>
                        <th class="p-5">Deadline</th>
                        <th class="p-5 text-center">Priority</th>
                        <th class="p-5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($tasks as $task)
                    <tr x-show="matchesSearch('{{ $task->title }}', '{{ $task->description }}')" class="hover:bg-gray-50 transition">
                        <td class="p-5">
                            <div class="font-bold text-gray-900 text-lg">{{ $task->title }}</div>
                            <p class="text-sm text-gray-500 truncate max-w-md">{{ $task->description }}</p>
                        </td>
                        <td class="p-5">
                            <div class="text-sm font-semibold text-indigo-600" x-data="timer('{{ $task->due_date->toIso8601String() }}')" x-init="start()">
                                <span x-text="timeLeft"></span>
                            </div>
                            <div class="text-[10px] text-gray-400">Ends: {{ $task->due_date->format('M d, Y') }}</div>
                        </td>
                        <td class="p-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                                {{ $task->priority == 'High' ? 'bg-red-100 text-red-600' : ($task->priority == 'Medium' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600') }}">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td class="p-5">
                            <div class="flex justify-center gap-4">
                                <button @click="openEditModal({{ json_encode($task) }})" class="text-indigo-600 hover:underline font-medium text-sm">Edit</button>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:underline font-medium text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Numbers -->
        <div class="mt-8">
            {{ $tasks->links() }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-20 py-10 bg-white border-t border-gray-200 text-center text-gray-400 text-sm">
        &copy; 2026 M. Waseem | BS Software Engineering | COMSATS University
    </footer>

    <!-- MODAL POPUP -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-xl rounded-3xl p-8 shadow-2xl overflow-hidden" @click.away="showModal = false">
            <h2 class="text-2xl font-bold mb-6 text-gray-900" x-text="isEdit ? 'Update Task' : 'Add New Task'"></h2>
            
            <form :action="isEdit ? `/tasks/${currentTask.id}` : '/tasks'" method="POST">
                @csrf
                <template x-if="isEdit"><input type="hidden" name="_method" value="PATCH"></template>

                <div class="grid grid-cols-1 gap-5">
                    <input type="text" name="title" x-model="currentTask.title" placeholder="Title" class="w-full border border-gray-300 rounded-xl p-3 outline-none focus:ring-2 ring-indigo-500" required>
                    <textarea name="description" x-model="currentTask.description" placeholder="Short description..." class="w-full border border-gray-300 rounded-xl p-3 outline-none focus:ring-2 ring-indigo-500"></textarea>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-400 ml-1">Start Date</label>
                            <input type="datetime-local" name="start_date" x-model="currentTask.start_date" class="w-full border border-gray-300 rounded-xl p-3 text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 ml-1">Due Date</label>
                            <input type="datetime-local" name="due_date" x-model="currentTask.due_date" class="w-full border border-gray-300 rounded-xl p-3 text-sm" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <select name="priority" x-model="currentTask.priority" class="w-full border border-gray-300 rounded-xl p-3 text-sm">
                            <option value="Low">Low Priority</option>
                            <option value="Medium">Medium Priority</option>
                            <option value="High">High Priority</option>
                        </select>
                        <select name="status" x-model="currentTask.status" class="w-full border border-gray-300 rounded-xl p-3 text-sm">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="button" @click="showModal = false" class="flex-1 bg-gray-100 py-3 rounded-xl hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition" x-text="isEdit ? 'Save Changes' : 'Create Task'"></button>
                </div>
            </form>
        </div>
    
    </div>
    <!-- JavaScript Logic -->
    <script>
        function taskManager() {
            return {
                search: '',
                showModal: false,
                isEdit: false,
                currentTask: {},
                
                openCreateModal() {
                    this.isEdit = false;
                    this.currentTask = { priority: 'Medium', status: 'Pending' };
                    this.showModal = true;
                },
                
                openEditModal(task) {
                    this.isEdit = true;
                    // Format dates for the datetime-local input
                    task.start_date = task.start_date ? task.start_date.substring(0,16) : '';
                    task.due_date = task.due_date ? task.due_date.substring(0,16) : '';
                    this.currentTask = {...task};
                    this.showModal = true;
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