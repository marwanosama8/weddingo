<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'user_id'=>$this->faker->numberBetween(1,4),
            'category_id'=>$this->faker->numberBetween(1,10),
            'rate'=>$this->faker->numberBetween(0,5),
            'gallery_limit'=>$this->faker->numberBetween(6,10),
            'other_categroy_id'=>$this->faker->numberBetween(1,10),
            'active'=>$this->faker->boolean(70),
            'business_name'=>$this->faker->jobTitle(),
            'social_provider'=>$this->faker->randomElement(['Instagram','Facebook','Painterest']),
            'social_url'=>$this->faker->domainName(),
            'business_type' => $this->faker->randomElement(['Workspace','Freelance']),
            'about_us_survey'=>$this->faker->sentence(8),
            'address_latitude'=>$this->faker->latitude(),
            'address_longitude'=>$this->faker->longitude(),
            
        ];
    }
}
