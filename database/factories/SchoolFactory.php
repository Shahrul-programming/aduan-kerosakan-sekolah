<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'), // Tambah field code (contoh: ABC123)
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'principal_name' => $this->faker->name, // Tambah principal_name
            'principal_phone' => $this->faker->phoneNumber, // Tambah principal_phone
            'hem_name' => $this->faker->name,
            'hem_phone' => $this->faker->phoneNumber,
        ];
    }
}
