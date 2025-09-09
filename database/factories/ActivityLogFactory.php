<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => $this->faker->sentence(),
            'complaint_id' => Complaint::factory(),
        ];
    }
}
