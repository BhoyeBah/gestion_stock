<?php

namespace Database\Seeders;

use App\Models\Units;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $units = [
            ['name' => 'Piece', 'code' => 'pcs'],
            ['name' => 'Kilogram', 'code' => 'kg'],
            ['name' => 'Litre', 'code' => 'L'],
            ['name' => 'Box', 'code' => 'box'],
        ];

         foreach ($units as $unit) {
            Units::create([
                'id' => (string) Str::uuid(),
                'name' => $unit['name'],
                'code' => $unit['code'],
            ]);
        }
    }
}
