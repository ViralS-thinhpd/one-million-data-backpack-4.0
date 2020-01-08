<?php

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 10000 ;$i++)
        {
            Book::create([
                'name' => $faker->name,
                'author_id' => $faker->numberBetween(1, 1000000)
            ]);
        }
    }
}
