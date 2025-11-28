<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Admin
        $adminId = DB::table('users')->insertGetId([
            'name' => 'System Admin',
            'role' => 'admin',
            'phone' => '100-000-0000',
            'specialization' => null,
            'department' => 'Operations',
            'hire_date' => $now->toDateString(),
            'email' => 'admin@example.com',
            'email_verified_at' => $now,
            'password' => Hash::make('Password123!'),
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Engineer
        $engineerId = DB::table('users')->insertGetId([
            'name' => 'Primary Engineer',
            'role' => 'engineer',
            'phone' => '100-000-0001',
            'specialization' => 'HVAC',
            'department' => 'Field Service',
            'hire_date' => $now->copy()->subYears(1)->toDateString(),
            'email' => 'eng@example.com',
            'email_verified_at' => $now,
            'password' => Hash::make('Password123!'),
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Customers
        $customer1Id = DB::table('users')->insertGetId([
            'name' => 'Acme Corp',
            'role' => 'customer',
            'phone' => '200-000-0001',
            'specialization' => null,
            'department' => null,
            'hire_date' => null,
            'email' => 'cust@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('Password123!'),
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $customer2Id = DB::table('users')->insertGetId([
            'name' => 'Globex LLC',
            'role' => 'customer',
            'phone' => '200-000-0002',
            'specialization' => null,
            'department' => null,
            'hire_date' => null,
            'email' => 'cust2@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('Password123!'),
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}


