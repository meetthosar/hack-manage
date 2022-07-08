<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Permission;




class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return  array
     */
    public function definition()
    {
        return [
                                                'guard_name' => $this->faker->word(),
                                                            'name' => $this->faker->name(),
                                ];
    }
}
