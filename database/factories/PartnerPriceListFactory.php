<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PartnerPriceList>
 */
class PartnerPriceListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'partner_id' => $this->faker->numberBetween(1,50),
            'price' => $this->faker->numberBetween(50,250),
            'service' => $this->faker->jobTitle(),
        ];
    }
}
