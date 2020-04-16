<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $items = [
            [
                'name' => 'Noah',
                'email' => 'Noah',
                'password' => '$2y$10$pbGioB27dhLaZ3H5SPCjw.9a.vsIYPTWXSvUy.Nd6dR1uEPaj/0kS',
                'email_verified_at' => now(),
            ]
        ];


        foreach ($items as $item) {
            \App\User::create($item);
        }
    }
}
