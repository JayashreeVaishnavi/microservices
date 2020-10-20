<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserDetail::factory()->has(Account::factory()->count(3))
            ->count(50)->create();
    }
}
