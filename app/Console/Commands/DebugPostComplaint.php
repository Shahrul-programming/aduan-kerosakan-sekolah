<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Http\Request;

class DebugPostComplaint extends Command
{
    protected $signature = 'debug:post-complaint {--user=11}';
    protected $description = 'Simulate a POST to complaints.store as a specific user id (for debugging middleware)';

    public function handle()
    {
        $userId = $this->option('user');
        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found: ' . $userId);
            return 1;
        }

        // Create a fake request
        $request = Request::create(route('complaints.store'), 'POST', [
            'title' => 'Debug title',
            'school_id' => $user->school_id ?? 1,
            'category' => 'lain-lain',
            'description' => 'Debug description',
            'priority' => 'sederhana',
        ]);

        // Use the acting middleware by setting the user on the request
        auth()->login($user);

        $this->info('Acting as user id: ' . $user->id . ' role: ' . $user->role);

        $response = app()->handle($request);

        $this->info('Response status: ' . $response->getStatusCode());
        $this->info('Response headers: ' . json_encode($response->headers->all()));

        return 0;
    }
}
