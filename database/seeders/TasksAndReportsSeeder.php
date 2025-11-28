<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TasksAndReportsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $adminId = DB::table('users')->where('email', 'admin@example.com')->value('id');
        $engineerId = DB::table('users')->where('email', 'eng@example.com')->value('id');
        $customer1Id = DB::table('users')->where('email', 'cust@example.com')->value('id');
        $customer2Id = DB::table('users')->where('email', 'cust2@example.com')->value('id');

        // Helper: get first N asset ids for a given customer (through buildings)
        $getAssetsForCustomer = function (int $customerId, int $limit) {
            return DB::table('assets')
                ->join('buildings', 'assets.building_id', '=', 'buildings.id')
                ->where('buildings.user_id', $customerId)
                ->orderBy('assets.id')
                ->limit($limit)
                ->pluck('assets.id')
                ->all();
        };

        $c1Assets = $getAssetsForCustomer($customer1Id, 2);
        $c2Assets = $getAssetsForCustomer($customer2Id, 2);

        // Create three tasks (two for customer1, one for customer2)
        $tasks = [];
        $tasks[] = [
            'customer_id' => $customer1Id,
            'asset_id' => $c1Assets[0] ?? null,
            'created_by_id' => $adminId,
            'assigned_to_id' => $engineerId,
            'title' => 'Quarterly maintenance',
            'description' => 'Perform standard preventive maintenance.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'type' => 'Maintenance',
            'notes' => null,
            'preferred_date' => $now->copy()->addDays(1)->toDateString(),
            'preferred_time_slot' => '09:00-12:00',
            'scheduling_details' => null,
            'special_instructions' => 'Coordinate with site manager.',
            'request_date' => $now->copy()->subDays(1),
            'assigned_date' => $now->copy()->subHours(6),
            'completed_date' => null,
        ];
        $tasks[] = [
            'customer_id' => $customer1Id,
            'asset_id' => $c1Assets[1] ?? $c1Assets[0] ?? null,
            'created_by_id' => $adminId,
            'assigned_to_id' => $engineerId,
            'title' => 'Boiler leak inspection',
            'description' => 'Investigate reported minor leak.',
            'status' => 'completed',
            'priority' => 'high',
            'type' => 'Repair',
            'notes' => 'Replaced seal and tested.',
            'preferred_date' => null,
            'preferred_time_slot' => null,
            'scheduling_details' => null,
            'special_instructions' => null,
            'request_date' => $now->copy()->subDays(3),
            'assigned_date' => $now->copy()->subDays(2),
            'completed_date' => $now->copy()->subDay(),
        ];
        $tasks[] = [
            'customer_id' => $customer2Id,
            'asset_id' => $c2Assets[0] ?? null,
            'created_by_id' => $adminId,
            'assigned_to_id' => null,
            'title' => 'Filter replacement request',
            'description' => 'Customer requested routine filter changes.',
            'status' => 'pending',
            'priority' => 'low',
            'type' => 'Maintenance',
            'notes' => null,
            'preferred_date' => $now->copy()->addDays(3)->toDateString(),
            'preferred_time_slot' => '13:00-16:00',
            'scheduling_details' => null,
            'special_instructions' => null,
            'request_date' => $now,
            'assigned_date' => null,
            'completed_date' => null,
        ];

        $taskIds = [];
        foreach ($tasks as $t) {
            $taskIds[] = DB::table('tasks')->insertGetId(array_merge($t, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // Task parts usage
        $partIds = DB::table('parts')->orderBy('id')->pluck('id')->all();
        if (!empty($taskIds)) {
            // For task 1 (in_progress): use filter and thermostat
            if (isset($taskIds[0])) {
                DB::table('task_parts')->insert([
                    [
                        'task_id' => $taskIds[0],
                        'part_id' => $partIds[0] ?? null,
                        'quantity_used' => 2,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'task_id' => $taskIds[0],
                        'part_id' => $partIds[1] ?? null,
                        'quantity_used' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ]);
            }
            // For task 2 (completed): use seal kit
            if (isset($taskIds[1]) && isset($partIds[2])) {
                DB::table('task_parts')->insert([
                    [
                        'task_id' => $taskIds[1],
                        'part_id' => $partIds[2],
                        'quantity_used' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                ]);
            }
        }

        // Service reports: for in_progress and completed tasks (2 reports)
        if (isset($taskIds[0])) {
            DB::table('service_reports')->insert([
                'task_id' => $taskIds[0],
                'submitted_by_id' => $engineerId,
                'reviewed_by_id' => null,
                'title' => 'Mid-service update',
                'content' => 'Work in progress. Filters replaced; thermostat calibration pending.',
                'status' => 'submitted',
                'attachment_url' => null,
                'submitted_date' => $now->copy()->subHour(),
                'approved_date' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        if (isset($taskIds[1])) {
            DB::table('service_reports')->insert([
                'task_id' => $taskIds[1],
                'submitted_by_id' => $engineerId,
                'reviewed_by_id' => $adminId,
                'title' => 'Completed repair summary',
                'content' => 'Seal replaced, system pressure normalized, no leaks detected.',
                'status' => 'approved',
                'attachment_url' => null,
                'submitted_date' => $now->copy()->subDay(),
                'approved_date' => $now->copy()->subHours(20),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}


