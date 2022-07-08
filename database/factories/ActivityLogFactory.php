<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ActivityLog;




class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return  array
     */
    public function definition()
    {
        return [
                                                'batch_uuid' => $this->faker->word(),
                                                'causer_id' => $this->faker->randomDigit(),
                                                'causer_type' => $this->faker->word(),
                                                            'description' => $this->faker->sentence(),
                                                            'event' => $this->faker->word(),
                                                            'log_name' => $this->faker->word(),
                                                            'properties' => json_encode(["faker_data"]),
                                                'subject_id' => $this->faker->randomDigit(),
                                                'subject_type' => $this->faker->word(),
                                ];
    }
}
