<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoSingleSeedSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Clean tables (child -> parent) to ensure a single coherent dataset
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('invoice_line_items')->truncate();
        DB::table('service_reports')->truncate();
        DB::table('task_parts')->truncate();
        DB::table('part_inventory')->truncate();
        DB::table('invoices')->truncate();
        DB::table('tasks')->truncate();
        DB::table('assets')->truncate();
        DB::table('buildings')->truncate();
        DB::table('contacts')->truncate();
        DB::table('parts')->truncate();
        DB::table('inventory_locations')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Users: admin, engineer, customer (all fields filled)
        $adminId = DB::table('users')->insertGetId([
            'name' => 'System Admin',
            'role' => 'admin',
            'phone' => '100-000-0000',
            'specialization' => 'Operations Management',
            'department' => 'Operations',
            'hire_date' => '2023-01-03',
            'email' => 'admin@example.com',
            'email_verified_at' => $now,
            'password' => Hash::make('Password123!'),
            'remember_token' => 'adminTokenX',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $engineerId = DB::table('users')->insertGetId([
            'name' => 'Primary Engineer',
            'role' => 'engineer',
            'phone' => '100-000-0001',
            'specialization' => 'HVAC',
            'department' => 'Field Service',
            'hire_date' => '2023-05-15',
            'email' => 'eng@example.com',
            'email_verified_at' => $now,
            'password' => Hash::make('Password123!'),
            'remember_token' => 'engineerTokenX',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $customerId = DB::table('users')->insertGetId([
            'name' => 'Acme Corp',
            'role' => 'customer',
            'phone' => '200-000-0001',
            'specialization' => 'Facility Management',
            'department' => 'Client Operations',
            'hire_date' => '2024-02-01',
            'email' => 'cust@example.com',
            'email_verified_at' => $now,
            'password' => Hash::make('Password123!'),
            'remember_token' => 'customerTokenX',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Contact (one)
        DB::table('contacts')->insert([
            'user_id' => $customerId,
            'name' => 'Acme Site Manager',
            'email' => 'site.manager@acme.example',
            'phone' => '300-000-0001',
            'role' => 'Manager',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Building (one)
        $buildingId = DB::table('buildings')->insertGetId([
            'user_id' => $customerId,
            'name' => 'Acme HQ Tower',
            'address' => '100 Main St, Metropolis',
            'room_number' => 'A1',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Asset (one)
        $assetId = DB::table('assets')->insertGetId([
            'building_id' => $buildingId,
            'name' => 'Air Handler AH-150',
            'manufacturer' => 'AirMax',
            'model' => 'AM-150',
            'installation_date' => '2024-01-15',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Part (one)
        $partId = DB::table('parts')->insertGetId([
            'name' => 'Filter 20x20',
            'number' => 'FLT-20',
            'manufacturer' => 'AirMax',
            'unit_price' => 15.50,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Inventory Location (one)
        $locationId = DB::table('inventory_locations')->insertGetId([
            'name' => 'Main Warehouse',
            'code' => 'WH-A',
            'contact_name' => 'Warehouse Lead',
            'contact_phone' => '400-000-0001',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Part Inventory (one)
        DB::table('part_inventory')->insert([
            'part_id' => $partId,
            'location_id' => $locationId,
            'quantity_on_hand' => 50,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Task (one) - all timestamps set (no nulls)
        $taskId = DB::table('tasks')->insertGetId([
            'customer_id' => $customerId,
            'asset_id' => $assetId,
            'created_by_id' => $adminId,
            'assigned_to_id' => $engineerId,
            'title' => 'Quarterly HVAC Maintenance',
            'description' => 'Perform preventive maintenance on AH-150.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'type' => 'Maintenance',
            'notes' => 'Use new filter batch FLT-20.',
            'preferred_date' => '2025-12-01',
            'preferred_time_slot' => '09:00-12:00',
            'scheduling_details' => 'Coordinate with Acme Site Manager at reception',
            'special_instructions' => 'Follow safety checklist and lockout/tagout',
            'request_date' => $now->copy()->subDays(2)->toDateTimeString(),
            'assigned_date' => $now->copy()->subDay()->toDateTimeString(),
            'completed_date' => '2025-12-02 16:00:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Task Parts (one)
        DB::table('task_parts')->insert([
            'task_id' => $taskId,
            'part_id' => $partId,
            'quantity_used' => 2,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Service Report (one) with attachment_url
        DB::table('service_reports')->insert([
            'task_id' => $taskId,
            'submitted_by_id' => $engineerId,
            'reviewed_by_id' => $adminId,
            'title' => 'Quarterly HVAC Maintenance Report',
            'content' => 'Filters replaced, belts inspected, motors lubricated. System operational.',
            'status' => 'approved',
            'attachment_url' => '/storage/service_reports/demo.pdf',
            'submitted_date' => $now->copy()->subHours(3)->toDateTimeString(),
            'approved_date' => $now->copy()->subHours(2)->toDateTimeString(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Invoice (one) and Line Items (two) – amount matches items
        $invoiceAmount = (15.50 * 2) + 120.00;
        $invoiceId = DB::table('invoices')->insertGetId([
            'user_id' => $customerId,
            'invoice_date' => $now->toDateString(),
            'due_date' => $now->copy()->addDays(7)->toDateString(),
            'amount' => $invoiceAmount,
            'status' => 'sent',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('invoice_line_items')->insert([
            [
                'invoice_id' => $invoiceId,
                'name' => 'Filter 20x20 (2 units)',
                'quantity' => 2,
                'unit_price' => 15.50,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'invoice_id' => $invoiceId,
                'name' => 'Service labor (2h)',
                'quantity' => 1,
                'unit_price' => 120.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}


