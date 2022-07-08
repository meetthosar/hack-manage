<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;




class UserFactory extends Factory
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
                                                            'current_team_id' => \App\Models\Current_team::inRandomOrder()->firstOrFail()->id,
                                                            'deleted_by' => $this->faker->randomNumber(),
                                                            'email' => $this->faker->unique()->safeEmail(),
                                                            'name' => $this->faker->name(),
                                                            'profile_photo_path' => $this->faker->word(),
                                                            'two_factor_confirmed_at' => $this->faker->dateTime(),
                                                            'updated_by' => $this->faker->randomNumber(),
                                ];
    }
}
