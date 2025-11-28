<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerStructureSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $customers = DB::table('users')
            ->where('role', 'customer')
            ->whereIn('email', ['cust@example.com', 'cust2@example.com'])
            ->get(['id', 'name', 'email']);

        foreach ($customers as $customer) {
            // Contacts (2 per customer)
            DB::table('contacts')->insert([
                [
                    'user_id' => $customer->id,
                    'name' => $customer->name.' Primary Contact',
                    'email' => 'support+'.str_replace('@', '+', $customer->email),
                    'phone' => '300-000-0001',
                    'role' => 'Manager',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'user_id' => $customer->id,
                    'name' => $customer->name.' Secondary Contact',
                    'email' => 'ops+'.str_replace('@', '+', $customer->email),
                    'phone' => '300-000-0002',
                    'role' => 'Supervisor',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            // Buildings (2 per customer)
            $buildingAId = DB::table('buildings')->insertGetId([
                'user_id' => $customer->id,
                'name' => $customer->name.' HQ',
                'address' => '100 Main St',
                'room_number' => 'A1',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $buildingBId = DB::table('buildings')->insertGetId([
                'user_id' => $customer->id,
                'name' => $customer->name.' Plant',
                'address' => '200 Industrial Ave',
                'room_number' => 'B2',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Assets (2 per building)
            foreach ([$buildingAId, $buildingBId] as $idx => $buildingId) {
                DB::table('assets')->insert([
                    [
                        'building_id' => $buildingId,
                        'name' => 'Boiler Unit '.($idx + 1),
                        'manufacturer' => 'ThermoCo',
                        'model' => 'TH-'.($idx + 1).'00',
                        'installation_date' => $now->copy()->subYears(2)->toDateString(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'building_id' => $buildingId,
                        'name' => 'Air Handler '.($idx + 1),
                        'manufacturer' => 'AirMax',
                        'model' => 'AM-'.($idx + 1).'50',
                        'installation_date' => $now->copy()->subYears(1)->toDateString(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ]);
            }
        }
    }
}


