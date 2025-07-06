<?php

namespace Database\Factories;

use App\Enums\Region;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Conference;
use App\Models\Venue;

class ConferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Conference::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'start_date' => now()->addMonths(9),
            'end_date' => now()->addMonths(9)->addDays(2),
            'status' => fake()->randomElement(Status::class),
            'region' => fake()->randomElement(Region::class),
            'venue_id' => null,
        ];
    }
}
