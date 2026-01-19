<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $provinces = [
            'Banteay Meanchey',
            'Battambang',
            'Kampong Cham',
            'Kampong Chhnang',
            'Kampong Speu',
            'Kampong Thom',
            'Kampot',
            'Kandal',
            'Kep',
            'Koh Kong',
            'Kratie',
            'Mondulkiri',
            'Pailin',
            'Phnom Penh',
            'Preah Sihanouk',
            'Preah Vihear',
            'Prey Veng',
            'Pursat',
            'Ratanakiri',
            'Siem Reap',
            'Stung Treng',
            'Svay Rieng',
            'Takeo',
            'Tboung Khmum',
            'Other'
        ];

        foreach ($provinces as $province) {
            \App\Models\Province::firstOrCreate(['name' => $province]);
        }
    }
}
