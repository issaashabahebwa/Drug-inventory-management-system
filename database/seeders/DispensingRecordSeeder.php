<?php

namespace Database\Seeders;

use App\Models\DispensingRecord;
use App\Models\Drug;
use App\Models\StockBatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DispensingRecordSeeder extends Seeder
{
    public function run(): void
    {
        $pharmacist  = User::where('role', User::ROLE_PHARMACIST)->first();
        $pharmacist2 = User::where('role', User::ROLE_PHARMACIST)->skip(1)->first() ?? $pharmacist;

        // Sample OPD reference numbers (anonymised)
        $opds = [
            'OPD-2025-10041', 'OPD-2025-10042', 'OPD-2025-10043', 'OPD-2025-10044',
            'OPD-2025-10045', 'OPD-2025-10046', 'OPD-2025-10047', 'OPD-2025-10048',
            'OPD-2025-10049', 'OPD-2025-10050',
        ];

        $records = [
            ['drug' => 'Paracetamol',         'qty' => 24,  'days_ago' => 1,  'opd' => $opds[0], 'by' => $pharmacist],
            ['drug' => 'Amoxicillin',          'qty' => 21,  'days_ago' => 1,  'opd' => $opds[1], 'by' => $pharmacist],
            ['drug' => 'Co-trimoxazole',       'qty' => 14,  'days_ago' => 1,  'opd' => $opds[2], 'by' => $pharmacist2],
            ['drug' => 'Artemether-Lumefantrine (AL)', 'qty' => 24, 'days_ago' => 1, 'opd' => $opds[3], 'by' => $pharmacist2],
            ['drug' => 'Metronidazole',        'qty' => 21,  'days_ago' => 2,  'opd' => $opds[4], 'by' => $pharmacist],
            ['drug' => 'Paracetamol',          'qty' => 12,  'days_ago' => 2,  'opd' => $opds[5], 'by' => $pharmacist],
            ['drug' => 'Folic Acid',           'qty' => 30,  'days_ago' => 2,  'opd' => $opds[6], 'by' => $pharmacist2],
            ['drug' => 'Ferrous Sulphate',     'qty' => 30,  'days_ago' => 2,  'opd' => $opds[7], 'by' => $pharmacist2],
            ['drug' => 'Co-trimoxazole',       'qty' => 14,  'days_ago' => 3,  'opd' => $opds[8], 'by' => $pharmacist],
            ['drug' => 'Amlodipine',           'qty' => 30,  'days_ago' => 3,  'opd' => $opds[9], 'by' => $pharmacist],
            ['drug' => 'Metformin',            'qty' => 60,  'days_ago' => 4,  'opd' => $opds[0], 'by' => $pharmacist2],
            ['drug' => 'Ciprofloxacin',        'qty' => 14,  'days_ago' => 4,  'opd' => $opds[1], 'by' => $pharmacist],
            ['drug' => 'Paracetamol',          'qty' => 24,  'days_ago' => 5,  'opd' => $opds[2], 'by' => $pharmacist],
            ['drug' => 'Paracetamol Syrup',    'qty' => 2,   'days_ago' => 5,  'opd' => $opds[3], 'by' => $pharmacist2],
            ['drug' => 'Oral Rehydration Salts (ORS)', 'qty' => 5, 'days_ago' => 5, 'opd' => $opds[4], 'by' => $pharmacist2],
            ['drug' => 'Omeprazole',           'qty' => 14,  'days_ago' => 6,  'opd' => $opds[5], 'by' => $pharmacist],
            ['drug' => 'Amoxicillin',          'qty' => 21,  'days_ago' => 7,  'opd' => $opds[6], 'by' => $pharmacist],
            ['drug' => 'Artemether-Lumefantrine (AL)', 'qty' => 24, 'days_ago' => 7, 'opd' => $opds[7], 'by' => $pharmacist2],
            ['drug' => 'Lamivudine + Tenofovir + Dolutegravir', 'qty' => 30, 'days_ago' => 7, 'opd' => $opds[8], 'by' => $pharmacist],
            ['drug' => 'Paracetamol',          'qty' => 24,  'days_ago' => 8,  'opd' => $opds[9], 'by' => $pharmacist2],
            ['drug' => 'Metronidazole',        'qty' => 14,  'days_ago' => 10, 'opd' => $opds[0], 'by' => $pharmacist],
            ['drug' => 'Folic Acid',           'qty' => 30,  'days_ago' => 10, 'opd' => $opds[1], 'by' => $pharmacist2],
            ['drug' => 'Co-trimoxazole',       'qty' => 14,  'days_ago' => 12, 'opd' => $opds[2], 'by' => $pharmacist],
            ['drug' => 'Ciprofloxacin',        'qty' => 10,  'days_ago' => 14, 'opd' => $opds[3], 'by' => $pharmacist],
        ];

        foreach ($records as $record) {
            $drug  = Drug::where('name', $record['drug'])->first();
            $batch = $drug?->activeBatches()->first();
            $user  = $record['by'];

            if (!$drug || !$batch || !$user) continue;

            $dispDate = Carbon::today()->subDays($record['days_ago']);

            DispensingRecord::create([
                'drug_id'            => $drug->id,
                'stock_batch_id'     => $batch->id,
                'dispensed_by'       => $user->id,
                'patient_reference'  => $record['opd'],
                'quantity_dispensed' => $record['qty'],
                'dispensed_date'     => $dispDate,
                'dispensed_time'     => '09:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
