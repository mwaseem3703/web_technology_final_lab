<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Light Theme specific overrides */
        [data-bs-theme="light"] body { background-color: #f2edf3; }
        [data-bs-theme="light"] .navbar-purple { background-color: #ffffff; box-shadow: 0px 5px 21px -5px rgba(0, 0, 0, 0.05); }
        [data-bs-theme="light"] .card-white { background: #fff; box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.03); border: none; }
        
        /* Dark Theme specific overrides */
        [data-bs-theme="dark"] body { background-color: #121212; }
        [data-bs-theme="dark"] .navbar-purple { background-color: #1e1e1e; border-bottom: 1px solid #333; }
        [data-bs-theme="dark"] .card-white { background: #1e1e1e; border: 1px solid #333; box-shadow: none; }
        [data-bs-theme="dark"] .table { color: #e0e0e0; }
        [data-bs-theme="dark"] .table-hover tbody tr:hover { background-color: #2a2a2a; color: #fff;}

        /* Universal Components */
        .navbar-purple { height: 70px; transition: background-color 0.3s ease; }
        .navbar-brand-text { color: #b66dff; font-weight: 700; font-size: 1.5rem; letter-spacing: 1px; }
        
        .card-stretch { height: 100%; border: none; border-radius: 10px; color: white; position: relative; overflow: hidden; }
        .bg-gradient-danger { background: linear-gradient(to right, #ffbf96, #fe7096); }
        .bg-gradient-info { background: linear-gradient(to right, #90caf9, #047edf 99%); }
        .bg-gradient-success { background: linear-gradient(to right, #84d9d2, #07cdae); }
        
        .card-img-absolute { position: absolute; top: 0; right: 0; height: 100%; opacity: 0.2; }
        .card-white { border-radius: 10px; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .table th { border-top: none; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; color: #737F8B; }
        
        .activity-feed { border-left: 2px solid #e9ecef; padding-left: 15px; margin-left: 10px; }
        [data-bs-theme="dark"] .activity-feed { border-left-color: #444; }
        .activity-item { position: relative; margin-bottom: 20px; }
        .activity-item::before {
            content: ''; position: absolute; left: -21px; top: 2px;
            width: 10px; height: 10px; border-radius: 50%; background: #b66dff;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-purple sticky-top px-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#b66dff" class="bi bi-layers-fill" viewBox="0 0 16 16">
              <path d="M7.765 1.559a.5.5 0 0 1 .47 0l7.5 4a.5.5 0 0 1 0 .882l-7.5 4a.5.5 0 0 1-.47 0l-7.5-4a.5.5 0 0 1 0-.882l7.5-4z"/>
              <path d="m2.125 8.567-1.86.992a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882l-1.86-.992-5.17 2.756a1.5 1.5 0 0 1-1.41 0l-5.17-2.756z"/>
            </svg>
            <span class="navbar-brand-text">Admin Dashboard</span>
        </a>
       <div class="ms-auto d-flex align-items-center gap-3">
            <button id="themeToggle" class="btn btn-sm btn-light border rounded-circle shadow-sm" style="width: 35px; height: 35px;">
                🌙
            </button>

            <span class="text-secondary small fw-medium d-none d-md-inline">Welcome, {{ auth()->user()->name ?? 'System Admin' }}</span>
            
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4 px-md-5">
        
        @if(session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-0 text-body">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#b66dff" class="bi bi-house-door-fill me-2 mb-1" viewBox="0 0 16 16"><path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/></svg>
                    Dashboard
                </h3>
                <span class="text-muted small">Overview & Statistics</span>
            </div>
            
            <div>
               <a href="{{ route('admin.report.pdf') }}" target="_blank" class="btn btn-primary rounded-pill px-4" style="background-color: #b66dff; border-color: #b66dff;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download me-2" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/></svg>
    Download PDF Report
</a>
            </div>
        </div>

        <div class="row mb-4 g-4">
            <div class="col-md-4">
                <div class="card card-stretch bg-gradient-danger p-4 shadow-sm">
                    <svg class="card-img-absolute" viewBox="0 0 100 100" preserveAspectRatio="none"><circle cx="80" cy="20" r="40" fill="#ffffff" opacity="0.15"/><circle cx="90" cy="80" r="30" fill="#ffffff" opacity="0.15"/></svg>
                    <h6 class="fw-normal mb-3">Total Platform Tasks <i class="float-end fs-5">📈</i></h6>
                    <h2 class="mb-4 fw-bold">{{ number_format($globalStats['total_tasks']) }}</h2>
                    <h6 class="fw-normal small">Active records tracked</h6>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-stretch bg-gradient-info p-4 shadow-sm">
                    <svg class="card-img-absolute" viewBox="0 0 100 100" preserveAspectRatio="none"><circle cx="80" cy="20" r="40" fill="#ffffff" opacity="0.15"/><circle cx="90" cy="80" r="30" fill="#ffffff" opacity="0.15"/></svg>
                    <h6 class="fw-normal mb-3">Total Registered Users <i class="float-end fs-5">👥</i></h6>
                    <h2 class="mb-4 fw-bold">{{ number_format($globalStats['total_users']) }}</h2>
                    <h6 class="fw-normal small">Active community members</h6>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-stretch bg-gradient-success p-4 shadow-sm">
                    <svg class="card-img-absolute" viewBox="0 0 100 100" preserveAspectRatio="none"><circle cx="80" cy="20" r="40" fill="#ffffff" opacity="0.15"/><circle cx="90" cy="80" r="30" fill="#ffffff" opacity="0.15"/></svg>
                    <h6 class="fw-normal mb-3">Completed Milestones <i class="float-end fs-5">💎</i></h6>
                    <h2 class="mb-4 fw-bold">{{ number_format($globalStats['completed_tasks']) }}</h2>
                    <h6 class="fw-normal small">Successfully finished tasks</h6>
                </div>
            </div>
        </div>

        <div class="row mb-4 g-4">
            <div class="col-md-7">
                <div class="card card-white h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0 text-body">User Registrations Timeline</h5>
                        <span class="badge bg-secondary">Current Year</span>
                    </div>
                    <div style="height: 250px;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card card-white h-100 p-4">
                    <h5 class="fw-bold mb-4 text-body">Task Status Distribution</h5>
                    <div style="height: 200px;" class="d-flex justify-content-center">
                        <canvas id="donutChart"></canvas>
                    </div>
                    <div class="mt-4 d-flex flex-column gap-2 px-3">
                        <div class="d-flex justify-content-between small text-muted">
                            <span><span class="d-inline-block rounded-circle me-2" style="width:10px;height:10px;background:#07cdae;"></span>Completed Tasks</span>
                            <span class="fw-bold text-body" id="completedLabel">0</span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span><span class="d-inline-block rounded-circle me-2" style="width:10px;height:10px;background:#fe7096;"></span>Pending Tasks</span>
                            <span class="fw-bold text-body" id="pendingLabel">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-white p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0 text-body">Platform User Administration</h5>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control form-control-sm bg-body text-body border-secondary" placeholder="Search users...">
                            <a href="{{ route('admin.users.export') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                                Export CSV
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>User Profile</th>
                                    <th>Registration Date</th>
                                    <th>Time Spent</th>
                                    <th>Task Load</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-secondary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width:40px; height:40px; color: #b66dff;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-body">{{ $user->name }}</div>
                                                    <div class="small text-muted">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-secondary small">{{ $user->created_at->format('M d, Y') }}</span></td>

                                        <td><span class="badge bg-secondary bg-opacity-10 text-body border border-secondary border-opacity-25">{{ str_replace('-', '', $user->time_spent) }}</span></td>                                       
                                        <td>
                                            <div class="progress bg-secondary bg-opacity-10" style="height: 6px; width: 100%; max-width: 120px;">
                                                @php 
                                                    $percent = $user->total_tasks > 0 ? ($user->completed_tasks / $user->total_tasks) * 100 : 0;
                                                    $color = $percent > 75 ? '#07cdae' : ($percent > 40 ? '#90caf9' : '#fe7096');
                                                @endphp
                                                <div class="progress-bar rounded-pill" style="width: {{ $percent }}%; background-color: {{ $color }};"></div>
                                            </div>
                                            <div class="small text-muted mt-1">{{ $user->completed_tasks }} / {{ $user->total_tasks }} Done</div>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Purge this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No users found on the platform yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-white p-4 h-100">
                    <h5 class="fw-bold mb-4 text-body">Recent DB Activity</h5>
                    
                    <div class="activity-feed">
                        @forelse($recentLogs as $log)
                            <div class="activity-item">
                                <div class="fw-bold text-body small">
                                    {{ $log->status === 'Completed' ? 'Task Completed' : 'Task Created' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    "{{ $log->action }}" by {{ $log->user }}
                                </div>
                                <div class="text-secondary mt-1" style="font-size: 0.75rem;">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="text-muted small">No recent activity found in the database.</div>
                        @endforelse
                    </div>
                    
                    <button data-bs-toggle="modal" data-bs-target="#logsModal" class="btn btn-sm btn-light mt-auto fw-bold w-100" style="color: #b66dff !important; border-color: #e9ecef;">View All Logs</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer bg-transparent border-top py-4 mt-5">
        <div class="container-fluid d-flex justify-content-between align-items-center px-4">
            <span class="text-muted small">Copyright © 2026. All rights reserved.</span>
            <span class="text-muted small">Designed securely for Admin Operations.</span>
        </div>
    </footer>
<div class="modal fade" id="logsModal" tabindex="-1" aria-labelledby="logsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content bg-body text-body">
                <div class="modal-header border-secondary border-opacity-25 flex-wrap gap-2">
                    <h5 class="modal-title fw-bold" id="logsModalLabel">System Activity Logs</h5>
                    
                    <div class="d-flex ms-auto gap-2">
                        <input type="date" id="logDateFilter" class="form-control form-control-sm bg-body text-body border-secondary border-opacity-50 w-auto">
                        
                        <select id="logFilter" class="form-select form-select-sm w-auto bg-body text-body border-secondary border-opacity-50">
                            <option value="All">All Statuses</option>
                            <option value="Completed">Completed Only</option>
                            <option value="Pending">Pending Only</option>
                        </select>
                    </div>

                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="activity-feed" id="modalLogFeed">
                        @forelse($allLogs as $log)
                            <div class="activity-item log-entry" 
                                 data-status="{{ $log->status }}" 
                                 data-date="{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d') }}">
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-bold small">
                                        <span class="badge {{ $log->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }} me-2">{{ $log->status }}</span>
                                        {{ $log->status === 'Completed' ? 'Task Completed' : 'Task Created' }}
                                    </div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y - g:i A') }}
                                    </div>
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.85rem;">
                                    "<strong>{{ $log->action }}</strong>" by {{ $log->user }}
                                </div>
                            </div>
                        @empty
                            <div class="text-muted text-center py-4">No system logs available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
  <script>
        document.addEventListener("DOMContentLoaded", function () {
            const statusFilter = document.getElementById('logFilter');
            const dateFilter = document.getElementById('logDateFilter');
            const entries = document.querySelectorAll('.log-entry');
            
            function filterLogs() {
                const selectedStatus = statusFilter.value;
                const selectedDate = dateFilter.value; // Format: YYYY-MM-DD
                
                entries.forEach(entry => {
                    const entryStatus = entry.getAttribute('data-status');
                    const entryDate = entry.getAttribute('data-date');
                    
                    // Check if entry matches both filters
                    const statusMatch = (selectedStatus === 'All' || entryStatus === selectedStatus);
                    const dateMatch = (selectedDate === '' || entryDate === selectedDate);
                    
                    if (statusMatch && dateMatch) {
                        entry.style.display = 'block';
                    } else {
                        entry.style.display = 'none';
                    }
                });
            }

            // Listen for changes on BOTH inputs
            statusFilter.addEventListener('change', filterLogs);
            dateFilter.addEventListener('change', filterLogs);
        });
    </script>
    <script>
        // --- DARK MODE LOGIC ---
        document.addEventListener("DOMContentLoaded", function () {
            const htmlTag = document.documentElement;
            const themeToggleBtn = document.getElementById('themeToggle');
            
            // Check local storage for theme
            const currentTheme = localStorage.getItem('theme') || 'light';
            htmlTag.setAttribute('data-bs-theme', currentTheme);
            themeToggleBtn.innerText = currentTheme === 'dark' ? '☀️' : '🌙';

            themeToggleBtn.addEventListener('click', function () {
                const isDark = htmlTag.getAttribute('data-bs-theme') === 'dark';
                const newTheme = isDark ? 'light' : 'dark';
                
                htmlTag.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                this.innerText = newTheme === 'dark' ? '☀️' : '🌙';
                
                // Update charts slightly when theme changes
                Chart.instances.forEach(chart => {
                    chart.options.scales.x.ticks.color = newTheme === 'dark' ? '#888' : '#9c9fa6';
                    chart.options.scales.y.ticks.color = newTheme === 'dark' ? '#888' : '#9c9fa6';
                    chart.options.scales.y.grid.color = newTheme === 'dark' ? '#333' : '#ebedf2';
                    chart.update();
                });
            });

            // --- REAL CHART DATA INJECTION ---
            const registrationMonths = {!! json_encode($chartMonths ?? []) !!};
            const registrationData = {!! json_encode($chartCounts ?? []) !!};
            
            const completedTasks = {{ $globalStats['completed_tasks'] ?? 0 }};
            const totalTasks = {{ $globalStats['total_tasks'] ?? 0 }};
            const pendingTasks = totalTasks - completedTasks;

            document.getElementById('completedLabel').innerText = completedTasks;
            document.getElementById('pendingLabel').innerText = pendingTasks;

            // Chart colors based on theme
            const gridColor = currentTheme === 'dark' ? '#333' : '#ebedf2';
            const tickColor = currentTheme === 'dark' ? '#888' : '#9c9fa6';

            // 1. BAR CHART: User Registrations Timeline
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: registrationMonths.length ? registrationMonths : ['No Data'],
                    datasets: [{ 
                        label: 'New Registrations', 
                        data: registrationData.length ? registrationData : [0], 
                        backgroundColor: '#b66dff', 
                        borderRadius: 4,
                        barPercentage: 0.5 
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }, 
                    scales: {
                        x: { grid: { display: false }, ticks: { color: tickColor, font: {size: 11} } },
                        y: { 
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false }, 
                            ticks: { color: tickColor, font: {size: 11}, precision: 0 } 
                        }
                    }
                }
            });

            // 2. DONUT CHART: Task Status (Completed vs Pending)
            const donutCtx = document.getElementById('donutChart').getContext('2d');
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: totalTasks > 0 ? [completedTasks, pendingTasks] : [1, 1], // Show 50/50 grey if no data
                        backgroundColor: totalTasks > 0 ? ['#07cdae', '#fe7096'] : ['#e9ecef', '#dee2e6'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</body>
</html>