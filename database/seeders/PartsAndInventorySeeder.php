<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PartsAndInventorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Parts
        $parts = [
            ['name' => 'Filter 20x20', 'number' => 'FLT-20', 'manufacturer' => 'AirMax', 'unit_price' => 15.50],
            ['name' => 'Thermostat T100', 'number' => 'TH-100', 'manufacturer' => 'ThermoCo', 'unit_price' => 65.00],
            ['name' => 'Pump Seal Kit', 'number' => 'PSK-01', 'manufacturer' => 'HydraFlow', 'unit_price' => 28.75],
        ];
        $partIds = [];
        foreach ($parts as $p) {
            $partIds[] = DB::table('parts')->insertGetId([
                'name' => $p['name'],
                'number' => $p['number'],
                'manufacturer' => $p['manufacturer'],
                'unit_price' => $p['unit_price'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Inventory Locations
        $locAId = DB::table('inventory_locations')->insertGetId([
            'name' => 'Main Warehouse',
            'code' => 'WH-A',
            'contact_name' => 'Warehouse Lead',
            'contact_phone' => '400-000-0001',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $locBId = DB::table('inventory_locations')->insertGetId([
            'name' => 'Field Van 1',
            'code' => 'VAN-1',
            'contact_name' => 'Primary Engineer',
            'contact_phone' => '100-000-0001',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Part Inventory (each part stocked at both locations)
        foreach ($partIds as $idx => $partId) {
            DB::table('part_inventory')->insert([
                [
                    'part_id' => $partId,
                    'location_id' => $locAId,
                    'quantity_on_hand' => 50 - ($idx * 5),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'part_id' => $partId,
                    'location_id' => $locBId,
                    'quantity_on_hand' => 15 - ($idx * 2),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}


