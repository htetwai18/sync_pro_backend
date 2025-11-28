<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoicesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $customerId = DB::table('users')->where('email', 'cust@example.com')->value('id');

        // Helper to create invoice with items and compute amount
        $createInvoice = function (array $itemSpecs, string $status, string $dateOffset) use ($customerId, $now) {
            $invoiceDate = $now->copy()->modify($dateOffset)->toDateString();
            $dueDate = $now->copy()->modify($dateOffset.' +7 days')->toDateString();

            $amount = 0;
            foreach ($itemSpecs as $spec) {
                $amount += $spec['quantity'] * $spec['unit_price'];
            }

            $invoiceId = DB::table('invoices')->insertGetId([
                'user_id' => $customerId,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'amount' => $amount,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($itemSpecs as $spec) {
                DB::table('invoice_line_items')->insert([
                    'invoice_id' => $invoiceId,
                    'name' => $spec['name'],
                    'quantity' => $spec['quantity'],
                    'unit_price' => $spec['unit_price'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        };

        // Use part prices for consistency
        $parts = DB::table('parts')->whereIn('number', ['FLT-20','TH-100','PSK-01'])->pluck('unit_price', 'number');

        // Invoice 1 - sent
        $createInvoice([
            ['name' => 'Filter 20x20', 'quantity' => 3, 'unit_price' => (float) ($parts['FLT-20'] ?? 15.50)],
            ['name' => 'Thermostat T100', 'quantity' => 1, 'unit_price' => (float) ($parts['TH-100'] ?? 65.00)],
        ], 'sent', '-5 days');

        // Invoice 2 - paid
        $createInvoice([
            ['name' => 'Service labor (2h)', 'quantity' => 1, 'unit_price' => 120.00],
            ['name' => 'Pump Seal Kit', 'quantity' => 1, 'unit_price' => (float) ($parts['PSK-01'] ?? 28.75)],
        ], 'paid', '-20 days');
    }
}


