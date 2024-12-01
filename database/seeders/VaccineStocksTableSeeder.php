<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VaccineStocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the ID of an HC Admin (assuming there's already an HC Admin in the `users` table)
        $hcAdminId = DB::table('users')->where('role', 'HCAdmin')->value('id');

        // Seed vaccine stock data
        DB::table('vaccine_stocks')->insert([
            [
                'vaccine_name' => 'COVID-19 Vaccine (Pfizer)',
                'current_quantity' => 5000,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'COVID-19 Vaccine (Moderna)',
                'current_quantity' => 3000,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'BCG (Bacillus Calmette–Guérin)',
                'current_quantity' => 2000,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'Hepatitis B Vaccine',
                'current_quantity' => 2500,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'Measles, Mumps, Rubella (MMR)',
                'current_quantity' => 1800,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'DTP (Diphtheria, Tetanus, Pertussis)',
                'current_quantity' => 2200,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'Polio Vaccine (OPV/IPV)',
                'current_quantity' => 3000,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'Pneumococcal Vaccine',
                'current_quantity' => 1200,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'Rotavirus Vaccine',
                'current_quantity' => 1500,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vaccine_name' => 'Influenza Vaccine',
                'current_quantity' => 3500,
                'last_updated_by' => $hcAdminId,
                'last_updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
