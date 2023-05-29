<?php

namespace Database\Seeders;

use App\Models\Balance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Balance::create([
            'amount' => 0,
            'from' => Carbon::now(),
            'to' => Carbon::now()
        ]);
    }
}
