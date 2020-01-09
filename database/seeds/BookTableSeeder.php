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
        $authors = \App\Models\Author::all()->count();
        for ($i = 1; $i <= 10000 ;$i++)
        {
            Book::create([
                'name' => $faker->name,
                'author_id' => $faker->numberBetween(1, $authors)
            ]);
        }
    }
}
