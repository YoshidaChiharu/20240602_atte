<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use DateTime;
use App\Models\User;

class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $began_at = $this->faker
                    ->dateTimeBetween(new DateTime('8:00:00'), new DateTime('10:00:00'))
                    ->format('H:i:s');
        $finished_at = $this->faker
                    ->dateTimeBetween(new DateTime('18:00:00'), new DateTime('20:00:00'))
                    ->format('H:i:s');

        return [
            'user_id' => $this->faker->unique()->numberBetween(1, User::count()),
            'work_on' => Carbon::now()->format('Y-m-d'),
            'began_at' => $began_at,
            'finished_at' => $finished_at,
        ];
    }
}
