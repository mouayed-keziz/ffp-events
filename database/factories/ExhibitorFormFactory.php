<?php

namespace Database\Factories;

use App\Models\EventAnnouncement;
use App\Models\ExhibitorForm;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExhibitorFormFactory extends Factory
{
    protected $model = ExhibitorForm::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => [
                'en' => $this->faker->sentence(6), // Longer title
                'fr' => $this->faker->sentence(6),
                'ar' => $this->faker->sentence(6),
            ],
            'event_announcement_id' => EventAnnouncement::factory(),
            'fields' => $this->generateFields(),
            'currencies' => $this->generateCurrencies(),
        ];
    }

    /**
     * Generate a random set of fields for the exhibitor form.
     */
    protected function generateFields(): array
    {
        $fields = [];

        // Add a simple text field
        $fields[] = [
            'label' => [
                'en' => $this->faker->sentence(3), // Longer label
                'fr' => $this->faker->sentence(3),
                'ar' => $this->faker->sentence(3),
            ],
            'description' => [
                'en' => $this->faker->sentence(10), // Longer description
                'fr' => $this->faker->sentence(10),
                'ar' => $this->faker->sentence(10),
            ],
            'type' => 'text',
            'required' => $this->faker->boolean,
            'enable_pricing' => false,
        ];

        // Add a field with single price input (e.g., switch or checkbox)
        $enablePricing = $this->faker->boolean(70); // 70% chance of enabling pricing
        $fields[] = [
            'label' => [
                'en' => $this->faker->sentence(3),
                'fr' => $this->faker->sentence(3),
                'ar' => $this->faker->sentence(3),
            ],
            'description' => [
                'en' => $this->faker->sentence(10),
                'fr' => $this->faker->sentence(10),
                'ar' => $this->faker->sentence(10),
            ],
            'type' => $this->faker->randomElement(['switch', 'checkbox']),
            'required' => $this->faker->boolean,
            'enable_pricing' => $enablePricing,
            'prices' => $enablePricing ? [
                'DZD' => $this->faker->numberBetween(1000, 10000),
                'USD' => $this->faker->numberBetween(50, 500),
                'EUR' => $this->faker->numberBetween(50, 500),
            ] : null,
        ];

        // Add a field with multiple price inputs (e.g., single_option or multiple_options)
        $enablePricing = $this->faker->boolean(70); // 70% chance of enabling pricing
        $fields[] = [
            'label' => [
                'en' => $this->faker->sentence(3),
                'fr' => $this->faker->sentence(3),
                'ar' => $this->faker->sentence(3),
            ],
            'description' => [
                'en' => $this->faker->sentence(10),
                'fr' => $this->faker->sentence(10),
                'ar' => $this->faker->sentence(10),
            ],
            'type' => $this->faker->randomElement(['single_option', 'multiple_options']),
            'required' => $this->faker->boolean,
            'enable_pricing' => $enablePricing,
            'prices' => $enablePricing ? [
                'DZD' => null,
                'USD' => null,
                'EUR' => null,
            ] : null,
            'options' => [
                [
                    'value' => [
                        'en' => 'Option 1',
                        'fr' => 'Option 1',
                        'ar' => 'Option 1',
                    ],
                    'prices' => $enablePricing ? [
                        'DZD' => $this->faker->numberBetween(1000, 10000),
                        'USD' => $this->faker->numberBetween(50, 500),
                        'EUR' => $this->faker->numberBetween(50, 500),
                    ] : null,
                ],
                [
                    'value' => [
                        'en' => 'Option 2',
                        'fr' => 'Option 2',
                        'ar' => 'Option 2',
                    ],
                    'prices' => $enablePricing ? [
                        'DZD' => $this->faker->numberBetween(1000, 10000),
                        'USD' => $this->faker->numberBetween(50, 500),
                        'EUR' => $this->faker->numberBetween(50, 500),
                    ] : null,
                ],
            ],
        ];

        return $fields;
    }

    /**
     * Generate a random set of currencies (1, 2, or 3).
     */
    protected function generateCurrencies(): array
    {
        $currencies = ['DZD', 'USD', 'EUR'];
        return $this->faker->randomElements($currencies, $this->faker->numberBetween(1, 3));
    }
}
