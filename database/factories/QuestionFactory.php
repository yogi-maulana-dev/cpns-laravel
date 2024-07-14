<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_text' => implode("\n", $this->faker->sentences(rand(2, 5))),
            'question_image' => null,
            'discussion_image' => null,
            'discussion' => $this->faker->text(300),
            'question_type_id' => $this->faker->randomElement([1, 2, 3]),
            'order_index_correct_answer' => $this->faker->randomElement([1, 2, 3, 4, 5]),
        ];
    }
}
