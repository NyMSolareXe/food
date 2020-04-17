<?php

use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            ['item_name' => 'Ground Beef', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Milk', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Water', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Kleenex', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Lysol', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Chicken', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Peppers', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Tomato', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Cheese', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Salad', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Bagel', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Birthday Card', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Birthday Bag', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Tomato Paste', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Eggs', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Raspberry Jam', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Cucumber', 'item_refresh' => '+1 Week'],
            ['item_name' => 'Toilet Paper', 'item_refresh' => '+1 Week'],
        ];


        $user = \App\User::find(1);

        foreach ($items as $item) {
            // \App\User::create($item);
            $user->items()->create($item);
        }
    }
}
