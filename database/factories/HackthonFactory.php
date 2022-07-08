<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Hackthon;




class HackthonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return  array
     */
    public function definition()
    {
        return [
                                                'created_by' => $this->faker->randomNumber(),
                                                            'deleted_by' => $this->faker->randomNumber(),
                                                            'description' => $this->faker->sentence(),
                                                            'name' => $this->faker->name(),
                                                            'updated_by' => $this->faker->randomNumber(),
                                ];
    }
}
