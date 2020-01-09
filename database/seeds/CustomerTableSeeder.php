<?php

use App\Models\Book;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerTableSeeder extends Seeder
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
            Customer::create([
                'name' => $faker->name,
                'age' => $faker->numberBetween(10, 60),
                'address' => $faker->address,
            ]);
        }
        $books = Book::all()->count();
        $customers = Customer::all()->count();

        for ($i = 1; $i <= 10000 ;$i++)
        {
            DB::table('book_customer')->insert([
                'book_id' => $faker->numberBetween(1, $books),
                'customer_id' => $faker->numberBetween(1, $customers),
            ]);
        }

    }
}
