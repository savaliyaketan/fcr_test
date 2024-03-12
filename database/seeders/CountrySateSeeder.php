<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;

class CountrySateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*------------------------------------------
        --------------------------------------------
        US Country Data
        --------------------------------------------
        --------------------------------------------*/
        $country = Country::create(['name' => 'United State']);

        $state = State::create(['country_id' => $country->id, 'name' => 'Florida']);



        /*------------------------------------------
        --------------------------------------------
        India Country Data
        --------------------------------------------
        --------------------------------------------*/
        $country = Country::create(['name' => 'India']);

        $state = State::create(['country_id' => $country->id, 'name' => 'Gujarat']);
    }
}
