<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use DateTime;
use DateInterval;

class RestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $began_at = $finished_at = $this->faker
                    ->dateTimeBetween(new DateTime('12:00:00'), new DateTime('17:00:00'));
        $began_at = $began_at->format('H:i:s');
        $finished_at = $finished_at->add(DateInterval::createFromDateString('15 minute'))
                    ->format('H:i:s');

        return [
            'work_id' => $this->faker->numberBetween(1, 80),
            'began_at' => $began_at,
            'finished_at' => $finished_at,
        ];
    }
}
