<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * 1. DISPLAY ALL TASKS
     */


    /**
     * STREAM MATERIAL INLINE
     * Forces the browser to render the file live instead of triggering an automatic download.
     */
    public function viewMaterial(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!$task->file_path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($task->file_path)) {
            abort(404, 'Material file not found.');
        }

        $absolutePath = storage_path('app/public/' . $task->file_path);
        $mimeType = \Illuminate\Support\Facades\File::mimeType($absolutePath);

        // Header array forcing inline rendering context
        $headers = [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($absolutePath) . '"',
            'Cache-Control'       => 'public, max-age=600'
        ];

        return response()->file($absolutePath, $headers);
    }
    
    public function index()
    {
        $tasks = Auth::user()->tasks()->latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    /**
     * 2. ANALYTICS DASHBOARD
     */
    /**
     * UPDATED ANALYTICS DASHBOARD VIEW
     */
    public function dashboard()
    {
        $user = Auth::user();
        $stats = [
            'total' => $user->tasks()->count(),
            'pending' => $user->tasks()->where('status', 'Pending')->count(),
            'completed' => $user->tasks()->where('status', 'Completed')->count(),
            'high_priority' => $user->tasks()->where('priority', 'High')->where('status', 'Pending')->count(),
        ];
        
        return view('dashboard', compact('stats', 'user'));
    }

    /**
     * ON-THE-FLY DASHBOARD SMART CACHE AI GENERATOR
     */
    public function generateDashboardAnalytics(Request $request)
    {
        $user = Auth::user();
        
        // Cost & Latency Saver: If they don't explicitly force a refresh, check if we already have a saved analysis
        if (!$request->has('force_refresh') && !empty($user->ai_dashboard_analysis)) {
            return response()->json([
                'analysis' => $user->ai_dashboard_analysis,
                'cached_at' => $user->ai_analysis_cached_at ? $user->ai_analysis_cached_at->diffForHumans() : 'Recently'
            ]);
        }

        // Gather metrics context data about this student's workload to feed Gemini
        $total = $user->tasks()->count();
        $pending = $user->tasks()->where('status', 'Pending')->count();
        $completed = $user->tasks()->where('status', 'Completed')->count();
        $highPriority = $user->tasks()->where('priority', 'High')->where('status', 'Pending')->count();

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API Key configuration missing on server.'], 500);
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

            $prompt = "You are an elite university academic counselor. Analyze this software engineering student's workload metrics:
            - Total tasks assigned: {$total}
            - Pending tasks left: {$pending}
            - Completed milestones: {$completed}
            - Urgent/High-Priority pending tasks: {$highPriority}
            
            Provide a highly actionable executive profile analysis restricted to exactly 100 words. Split the response into two short, distinct sections:
            1. ANALYSIS: A direct, realistic summary evaluation of their productivity pacing based on these numbers.
            2. IMPROVEMENT SUGGESTIONS: Clear, high-impact bullet points telling them how to optimize their schedule and prioritize critical assignments to maintain top grades.";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                            ->timeout(15)
                            ->post($url, [
                                'contents' => [['parts' => [['text' => $prompt]]]]
                            ]);

            if ($response->successful()) {
                $analysisText = $response->json()['candidates'][0]['content']['parts'][0]['text'];

                // Clean and structure any unnecessary formatting wrappers
                $analysisText = trim(str_replace(['```text', '```'], '', $analysisText));

                // Save to the user row in the database with timestamps
                $user->update([
                    'ai_dashboard_analysis' => $analysisText,
                    'ai_analysis_cached_at' => now()
                ]);

                return response()->json([
                    'analysis' => $user->ai_dashboard_analysis,
                    'cached_at' => 'Just now'
                ]);
            }

            return response()->json(['error' => 'Google processing stream error.'], 500);

        } catch (\Exception $e) {
            return response()->json([
                'analysis' => "ANALYSIS: Your current workload shows active tasks pending execution maps. Critical core milestones are balanced across high-priority structures.\n\nIMPROVEMENT SUGGESTIONS:\n- Establish incremental testing deadlines.\n- Address high-priority backlogs ahead of standard criteria review intervals.",
                'cached_at' => 'Offline Fallback'
            ]);
        }
    }
    /**
     * 3. STORE NEW TASK WITH OPTIONAL FILE ATTACHMENT
     */
  /**
     * STORE NEW TASK
     */
    public function store(Request $request)
    {
        $incomingPriority = trim(str_replace('Priority', '', $request->input('priority')));
        $request->merge(['priority' => $incomingPriority]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|in:Low,Medium,High',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $task = Auth::user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'],
            'status' => 'Pending',
        ]);

        // FIX: Ensure file is valid and completely loaded before streaming
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $userId = auth()->id();
            $path = $request->file('attachment')->store("materials/{$userId}", 'public');
            $task->update(['file_path' => $path]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * UPDATE EXISTING TASK
     */
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|in:Low,Medium,High',
            'description' => 'nullable|string',
            'status' => 'required|in:Pending,Completed',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $filePath = $task->file_path;

        // Process explicit user-driven file deletions
        if ($request->input('remove_file') == 1) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = null;
        }

        // Process brand new replacement file uploads
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $userId = auth()->id();
            $filePath = $request->file('attachment')->store("materials/{$userId}", 'public');
        }

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'],
            'file_path' => $filePath
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * 5. SMART-CACHING ON-THE-FLY AI ENGINE
     */
    public function fetchAiGuidance(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Pull instantly out of local storage if it's already cached
        if (!empty($task->ai_time_estimate) && !empty($task->ai_study_plan)) {
            return response()->json([
                'time_estimate' => $task->ai_time_estimate,
                'study_plan' => $task->ai_study_plan,
                'study_tips' => $task->ai_study_tips
            ]);
        }

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'Missing configuration keys.'], 500);
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

            $prompt = "You are an elite academic coach. Analyze this student task:
            Title: '{$task->title}'
            Description: '{$task->description}'
            Priority: '{$task->priority}'
            
            Provide your response strictly in this exact JSON format. Keep answers extremely short, ultra-concise, and restricted to maximum 5-6 sentence bullet points so it fits on a clean UI card layout view:
            {
                \"time_estimate\": \"X hours total\",
                \"study_plan\": \"1. Brief action step. 2. Next quick milestone.\",
                \"study_tips\": \"Provide one single short target metric hint to secure an A grade.\"
            }";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                            ->timeout(12)
                            ->post($url, [
                                'contents' => [['parts' => [['text' => $prompt]]]]
                            ]);

            if ($response->successful()) {
                $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
                $cleanJson = str_replace(['```json', '```'], '', $rawText);
                $result = json_decode(trim($cleanJson), true);

                $time = $result['time_estimate'] ?? $result['time'] ?? '2-3 hours total';
                $plan = $result['study_plan'] ?? $result['plan'] ?? '1. Review requirements. 2. Build core modules.';
                $tips = $result['study_tips'] ?? $result['tips'] ?? 'Track system evaluation goals closely.';

                // Cache metrics straight into your database columns
                $task->update([
                    'ai_time_estimate' => $time,
                    'ai_study_plan' => $plan,
                    'ai_study_tips' => $tips
                ]);

                return response()->json([
                    'time_estimate' => $task->ai_time_estimate,
                    'study_plan' => $task->ai_study_plan,
                    'study_tips' => $task->ai_study_tips
                ]);
            }

            return response()->json(['error' => 'Google response fault.'], 500);

        } catch (\Exception $e) {
            return response()->json([
                'time_estimate' => '3 hours total',
                'study_plan' => '1. Review lecture details. 2. Execute verification maps builds.',
                'study_tips' => 'Analyze core target parameters to capture top marks grades targets.'
            ]);
        }
    }

    /**
     * 6. SHOW EDIT FORM
     */
    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);
        return view('tasks.edit', compact('task'));
    }

    /**
     * 7. DESTROY TASK WITH RESIDUAL STORAGE CLEANUP
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);

        // Wipe matching physical materials files off your laptop drive when tasks are deleted
        if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
            Storage::disk('public')->delete($task->file_path);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}