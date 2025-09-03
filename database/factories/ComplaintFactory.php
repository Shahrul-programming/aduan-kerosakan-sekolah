<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'complaint_number' => $this->faker->unique()->numerify('ADUAN-#####'),
            'school_id' => \App\Models\School::factory(),
            'user_id' => \App\Models\User::factory(),
            'category' => $this->faker->randomElement(['Elektrik', 'Bangunan', 'Air', 'Lain-lain']),
            'description' => $this->faker->paragraph(2),
            'image' => null,
            'video' => null,
            'priority' => $this->faker->randomElement(['tinggi', 'sederhana', 'rendah']),
            'status' => $this->faker->randomElement(['baru', 'semakan', 'assigned', 'proses', 'selesai']),
            'assigned_to' => null,
        ];
    }
}
