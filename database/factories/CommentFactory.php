<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        ##### SEEDING RELATIONSHIPS #####
        # Method 1
        // get model count...
        // generate random number between 1 and model count
        // if model count is 0
        // we should create a new record and retrieve the record id...
        // so, what if the id is string ????????
        // what if there are a deleted models ?


        # Method 2
        // get all model records...
        // randomly get one of the record, and get the id
        // so, what if we have 1 million record in our database.... ?

        return [
            'body' => [],
            'user_id' => FactoryHelper::getRandomModelId(User::class),
            'post_id' => FactoryHelper::getRandomModelId(Post::class)
        ];
    }
}
