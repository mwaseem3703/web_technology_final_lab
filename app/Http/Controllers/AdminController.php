<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf; // IMPORT DOMPDF
class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Global Stats
        $globalStats = [
            'total_users' => User::where(function ($query) {
                $query->where('is_admin', false)->orWhereNull('is_admin');
            })->count(),
            'total_tasks' => DB::table('tasks')->count(),
            'completed_tasks' => DB::table('tasks')->where('status', 'Completed')->count(),
        ];

        // 2. Users Table
        $users = User::where(function ($query) {
                $query->where('is_admin', false)->orWhereNull('is_admin');
            })
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => function ($query) {
                    $query->where('status', 'Completed');
                }
            ])
            ->latest()
            ->paginate(15);

        // 3. Real Recent Activity Logs (Assuming you have an 'activity_logs' or 'tasks' table to pull from)
        // Adjust this query based on your actual logging table structure.
        $recentLogs = DB::table('tasks') // Example using tasks as logs
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.title as action', 'users.name as user', 'tasks.created_at', 'tasks.status')
            ->latest('tasks.created_at')
            ->limit(5)
            ->get();

$allLogs = DB::table('tasks')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.title as action', 'users.name as user', 'tasks.created_at', 'tasks.status')
            ->latest('tasks.created_at')
            ->limit(100)
            ->get();
        // 4. Chart Data (Registrations by Month)
        $chartData = User::selectRaw('MONTHNAME(created_at) as month, count(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->pluck('count', 'month')->toArray();

        $chartMonths = array_keys($chartData);
        $chartCounts = array_values($chartData);

return view('admin.dashboard', compact('globalStats', 'users', 'recentLogs', 'allLogs', 'chartMonths', 'chartCounts'));    }

public function downloadPdf()
    {
        $globalStats = [
            'total_users' => User::where(function($q) { $q->where('is_admin', false)->orWhereNull('is_admin'); })->count(),
            'total_tasks' => DB::table('tasks')->count(),
            'completed_tasks' => DB::table('tasks')->where('status', 'Completed')->count(),
        ];

        $users = User::where(function($q) { $q->where('is_admin', false)->orWhereNull('is_admin'); })
            ->withCount(['tasks as total_tasks', 'tasks as completed_tasks' => function($q) { $q->where('status', 'Completed'); }])
            ->get();

        $logs = DB::table('tasks')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.title as action', 'users.name as user', 'tasks.created_at', 'tasks.status')
            ->latest('tasks.created_at')
            ->limit(50)
            ->get();

        $pdf = Pdf::loadView('admin.pdf_report', compact('globalStats', 'users', 'logs'));
        
        return $pdf->download('admin_system_report_'.date('Y-m-d').'.pdf');
    }

    /**
     * Export Users to CSV
     */
    public function exportUsers()
    {
        $users = User::where(function ($query) {
            $query->where('is_admin', false)->orWhereNull('is_admin');
        })->get();

        $response = new StreamedResponse(function () use ($users) {
            $handle = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($handle, ['ID', 'Name', 'Email', 'Registration Date', 'Time Spent']);

            // Add rows
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at->format('Y-m-d H:i:s'),
                    str_replace('-', '', $user->time_spent)
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="registered_users_'.date('Ymd').'.csv"');

        return $response;
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot delete active account.');
        }
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', "User purged successfully.");
    }
}