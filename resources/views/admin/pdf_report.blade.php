<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Report</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #b66dff; padding-bottom: 10px; }
        .stats-box { width: 30%; display: inline-block; padding: 15px; border: 1px solid #ddd; text-align: center; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2edf3; color: #b66dff; }
        h2 { margin-top: 40px; color: #343a40; }
    </style>
</head>
<body>

    <div class="header">
        <h1 style="color: #b66dff;">Admin System Report</h1>
        <p>Generated on: {{ date('F j, Y, g:i a') }}</p>
    </div>

    <div>
        <div class="stats-box">
            <h3>{{ $globalStats['total_tasks'] }}</h3>
            <p>Total Tasks</p>
        </div>
        <div class="stats-box" style="margin-left: 3%;">
            <h3>{{ $globalStats['total_users'] }}</h3>
            <p>Registered Users</p>
        </div>
        <div class="stats-box" style="margin-left: 3%;">
            <h3>{{ $globalStats['completed_tasks'] }}</h3>
            <p>Completed Tasks</p>
        </div>
    </div>

    <h2>Active Users Overview</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Tasks Done</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td>{{ $user->completed_tasks }} / {{ $user->total_tasks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Recent System Logs (Last 50)</h2>
    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Action</th>
                <th>User</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->user }}</td>
                <td>{{ $log->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>