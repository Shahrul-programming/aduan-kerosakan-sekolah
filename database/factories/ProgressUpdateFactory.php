<?php

namespace Database\Factories;

use App\Models\ProgressUpdate;
use App\Models\Complaint;
use App\Models\Contractor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressUpdateFactory extends Factory
{
    protected $model = ProgressUpdate::class;

    public function definition(): array
    {
        return [
            'complaint_id' => Complaint::factory(),
            'contractor_id' => Contractor::factory(),
            'description' => $this->faker->sentence(),
            'image_before' => null,
            'image_after' => null,
        ];
    }
}
