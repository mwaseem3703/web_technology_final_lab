<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with 10 academic testing tasks.
     */
    public function run(): void
    {
        // 1. Find or create your presentation user account
        $user = User::firstOrCreate(
            ['email' => 'waseem@example.com'],
            [
                'name' => 'M. Waseem',
                'password' => bcrypt('Password@123'), // Meets your strong password rule!
            ]
        );

        // 2. Define 10 diverse academic testing tasks
        $testTasks = [
            [
                'title' => 'Web Technology Lab Assignment 4',
                'description' => 'Implement full multi-user authentication isolation and data tracking constraints using Eloquent ORM.',
                'priority' => 'High',
                'due_date' => now()->addDays(3),
            ],
            [
                'title' => 'Software Design and Architecture Midterm Prep',
                'description' => 'Review structural and behavioral design patterns, specifically MVC, Singleton, and Strategy patterns.',
                'priority' => 'High',
                'due_date' => now()->addDays(5),
            ],
            [
                'title' => 'Operating Systems Process Synchronization Lab',
                'description' => 'Write a C program running on Ubuntu to demonstrate process creation and fork system calls.',
                'priority' => 'Medium',
                'due_date' => now()->addDays(2),
            ],
            [
                'title' => 'Compile Final Project Presentation Slides',
                'description' => 'Prepare a 7-minute slide deck highlighting the architecture, system testing suite, and AI integrations.',
                'priority' => 'High',
                'due_date' => now()->addDays(1),
            ],
            [
                'title' => 'Database Query Optimization Practice',
                'description' => 'Study indexing, query execution plans, and optimize slow select statements for the final assessment.',
                'priority' => 'Medium',
                'due_date' => now()->addDays(6),
            ],
            [
                'title' => 'Technical Documentation Review',
                'description' => 'Fix formatting issues, remove red borders from the table of contents, and finalize Overleaf LaTeX documents.',
                'priority' => 'Low',
                'due_date' => now()->addDays(4),
            ],
            [
                'title' => 'Assembly Language 8-bit Loop Lab',
                'description' => 'Write an x86 assembly program using TASM to process array elements using 8-bit registers.',
                'priority' => 'Medium',
                'due_date' => now()->addDays(3),
            ],
            [
                'title' => 'Next.js Analytics Event Setup',
                'description' => 'Integrate Google Tag and conversion event tracking scripts into the layout view file for a client project.',
                'priority' => 'Low',
                'due_date' => now()->addDays(7),
            ],
            [
                'title' => 'Flutter Mobile UI Overhaul',
                'description' => 'Apply clean Glassmorphism styling and implement smooth dark to light mode layout transitions.',
                'priority' => 'High',
                'due_date' => now()->addDays(4),
            ],
            [
                'title' => 'Object-Oriented Programming Revision',
                'description' => 'Revise core fundamentals: abstract classes, interfaces, method overriding, and polymorphism examples.',
                'priority' => 'Low',
                'due_date' => now()->addDays(10),
            ],
        ];

        $apiKey = env('GEMINI_API_KEY');

        $this->command->info('Seeding 10 tasks and generating AI insights...');

        // 3. Loop and automatically fetch AI predictions for each seeded task
        foreach ($testTasks as $taskData) {
            $task = $user->tasks()->create($taskData);

            if ($apiKey) {
                try {
                    $prompt = "You are an elite academic coach. Analyze this assignment/exam prep task:
                    Title: '{$task->title}'
                    Description: '{$task->description}'
                    Priority: '{$task->priority}'
                    
                    Provide your response strictly in this exact JSON format:
                    {
                        \"time_estimate\": \"X hours total\",
                        \"study_plan\": \"Step 1: Focus action. Step 2: Next action.\",
                        \"study_tips\": \"Provide 2 tailored tips to optimize retention and secure an A grade for this specific type of work.\"
                    }";

                    $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                        'contents' => [['parts' => [['text' => $prompt]]]],
                        'generationConfig' => ['responseMimeType' => 'application/json']
                    ]);

                    if ($response->successful()) {
                        $result = json_decode($response->json()['candidates'][0]['content']['parts'][0]['text'], true);
                        
                        $task->update([
                            'ai_time_estimate' => $result['time_estimate'] ?? 'N/A',
                            'ai_study_plan' => $result['study_plan'] ?? 'No plan generated.',
                            'ai_study_tips' => $result['study_tips'] ?? 'Review your syllabus thoroughly.'
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->command->warn("AI Generation failed for task: {$task->title}. Saving default values.");
                }
            }
        }

        $this->command->info('Database seeding completed successfully!');
    }
}