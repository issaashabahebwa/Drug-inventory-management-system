<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\Supplier;
use App\Models\StockBatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StockBatchSeeder extends Seeder
{
    public function run(): void
    {
        $nms      = Supplier::where('code', 'NMS-001')->first();
        $jms      = Supplier::where('code', 'JMS-001')->first();
        $stores   = User::where('role', User::ROLE_STORES)->first();
        $today    = Carbon::today();

        // Helper: creates a batch and updates drug total
        $createBatch = function (array $data) use ($stores) {
            $batch = StockBatch::create(array_merge([
                'received_by' => $stores?->id,
                'status'      => 'active',
            ], $data));

            // Update drug total_quantity
            $batch->drug->recalculateTotalQuantity();
        };

        // ── Paracetamol 500mg — two batches (one expiring soon) ────────
        $paracetamol = Drug::where('name', 'Paracetamol')->where('formulation', 'tablet')->first();
        if ($paracetamol) {
            $createBatch([
                'drug_id'              => $paracetamol->id,
                'supplier_id'          => $nms?->id,
                'batch_number'         => 'NMS-PCT-2024-001',
                'quantity_received'    => 1000,
                'quantity_remaining'   => 820,
                'manufacture_date'     => $today->copy()->subMonths(6),
                'expiry_date'          => $today->copy()->addMonths(18),
                'received_date'        => $today->copy()->subMonths(2),
                'delivery_note_number' => 'DN-2024-0391',
            ]);
            // Near-expiry batch (expires in 25 days — triggers critical alert)
            $createBatch([
                'drug_id'              => $paracetamol->id,
                'supplier_id'          => $nms?->id,
                'batch_number'         => 'NMS-PCT-2023-009',
                'quantity_received'    => 500,
                'quantity_remaining'   => 120,
                'manufacture_date'     => $today->copy()->subMonths(23),
                'expiry_date'          => $today->copy()->addDays(25),
                'received_date'        => $today->copy()->subMonths(11),
                'delivery_note_number' => 'DN-2023-0812',
            ]);
        }

        // ── Paracetamol Syrup ────────────────────────────────────────
        $pctSyrup = Drug::where('name', 'Paracetamol Syrup')->first();
        if ($pctSyrup) {
            $createBatch([
                'drug_id'            => $pctSyrup->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-PCTSYR-2024-002',
                'quantity_received'  => 200,
                'quantity_remaining' => 45,     // low stock — triggers warning
                'manufacture_date'   => $today->copy()->subMonths(4),
                'expiry_date'        => $today->copy()->addMonths(14),
                'received_date'      => $today->copy()->subMonths(3),
            ]);
        }

        // ── Amoxicillin 250mg ────────────────────────────────────────
        $amox = Drug::where('name', 'Amoxicillin')->first();
        if ($amox) {
            $createBatch([
                'drug_id'            => $amox->id,
                'supplier_id'        => $jms?->id,
                'batch_number'       => 'JMS-AMX-2024-010',
                'quantity_received'  => 800,
                'quantity_remaining' => 610,
                'manufacture_date'   => $today->copy()->subMonths(5),
                'expiry_date'        => $today->copy()->addMonths(19),
                'received_date'      => $today->copy()->subMonths(1),
            ]);
        }

        // ── Co-trimoxazole ───────────────────────────────────────────
        $cotrim = Drug::where('name', 'Co-trimoxazole')->first();
        if ($cotrim) {
            $createBatch([
                'drug_id'            => $cotrim->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-CTX-2024-003',
                'quantity_received'  => 500,
                'quantity_remaining' => 390,
                'manufacture_date'   => $today->copy()->subMonths(3),
                'expiry_date'        => $today->copy()->addMonths(21),
                'received_date'      => $today->copy()->subMonths(1),
            ]);
        }

        // ── Metronidazole ────────────────────────────────────────────
        $metro = Drug::where('name', 'Metronidazole')->first();
        if ($metro) {
            $createBatch([
                'drug_id'            => $metro->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-MTR-2024-005',
                'quantity_received'  => 400,
                'quantity_remaining' => 155,
                'manufacture_date'   => $today->copy()->subMonths(4),
                'expiry_date'        => $today->copy()->addMonths(16),
                'received_date'      => $today->copy()->subMonths(2),
            ]);
        }

        // ── Coartem (AL) ─────────────────────────────────────────────
        $al = Drug::where('name', 'Artemether-Lumefantrine (AL)')->first();
        if ($al) {
            $createBatch([
                'drug_id'            => $al->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-AL-2024-007',
                'quantity_received'  => 600,
                'quantity_remaining' => 480,
                'manufacture_date'   => $today->copy()->subMonths(2),
                'expiry_date'        => $today->copy()->addMonths(22),
                'received_date'      => $today->copy()->subWeeks(6),
            ]);
        }

        // ── Quinine tablets — CRITICALLY LOW ─────────────────────────
        $quinine = Drug::where('name', 'Quinine Sulphate')->first();
        if ($quinine) {
            $createBatch([
                'drug_id'            => $quinine->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-QNS-2023-015',
                'quantity_received'  => 200,
                'quantity_remaining' => 18,     // below reorder_level=100 → low stock
                'manufacture_date'   => $today->copy()->subMonths(14),
                'expiry_date'        => $today->copy()->addMonths(10),
                'received_date'      => $today->copy()->subMonths(8),
            ]);
        }

        // ── TLD (ARV) ────────────────────────────────────────────────
        $tld = Drug::where('name', 'Lamivudine + Tenofovir + Dolutegravir')->first();
        if ($tld) {
            $createBatch([
                'drug_id'            => $tld->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-TLD-2024-002',
                'quantity_received'  => 500,
                'quantity_remaining' => 320,
                'manufacture_date'   => $today->copy()->subMonths(2),
                'expiry_date'        => $today->copy()->addMonths(28),
                'received_date'      => $today->copy()->subWeeks(8),
            ]);
        }

        // ── Amlodipine ───────────────────────────────────────────────
        $amlo = Drug::where('name', 'Amlodipine')->first();
        if ($amlo) {
            $createBatch([
                'drug_id'            => $amlo->id,
                'supplier_id'        => $jms?->id,
                'batch_number'       => 'JMS-AML-2024-004',
                'quantity_received'  => 300,
                'quantity_remaining' => 210,
                'manufacture_date'   => $today->copy()->subMonths(3),
                'expiry_date'        => $today->copy()->addMonths(24),
                'received_date'      => $today->copy()->subMonths(2),
            ]);
        }

        // ── Metformin ─────────────────────────────────────────────────
        $metformin = Drug::where('name', 'Metformin')->first();
        if ($metformin) {
            $createBatch([
                'drug_id'            => $metformin->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-MET-2024-008',
                'quantity_received'  => 300,
                'quantity_remaining' => 230,
                'manufacture_date'   => $today->copy()->subMonths(2),
                'expiry_date'        => $today->copy()->addMonths(26),
                'received_date'      => $today->copy()->subMonths(1),
            ]);
        }

        // ── Folic Acid — expiring in 55 days ─────────────────────────
        $folic = Drug::where('name', 'Folic Acid')->first();
        if ($folic) {
            $createBatch([
                'drug_id'            => $folic->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-FOL-2023-020',
                'quantity_received'  => 500,
                'quantity_remaining' => 340,
                'manufacture_date'   => $today->copy()->subMonths(22),
                'expiry_date'        => $today->copy()->addDays(55),   // 60-day warning
                'received_date'      => $today->copy()->subMonths(10),
            ]);
        }

        // ── Ferrous Sulphate ─────────────────────────────────────────
        $ferrous = Drug::where('name', 'Ferrous Sulphate')->first();
        if ($ferrous) {
            $createBatch([
                'drug_id'            => $ferrous->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-FES-2024-006',
                'quantity_received'  => 500,
                'quantity_remaining' => 410,
                'manufacture_date'   => $today->copy()->subMonths(3),
                'expiry_date'        => $today->copy()->addMonths(21),
                'received_date'      => $today->copy()->subMonths(2),
            ]);
        }

        // ── ORS sachets ───────────────────────────────────────────────
        $ors = Drug::where('name', 'Oral Rehydration Salts (ORS)')->first();
        if ($ors) {
            $createBatch([
                'drug_id'            => $ors->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-ORS-2024-011',
                'quantity_received'  => 400,
                'quantity_remaining' => 285,
                'manufacture_date'   => $today->copy()->subMonths(2),
                'expiry_date'        => $today->copy()->addMonths(30),
                'received_date'      => $today->copy()->subWeeks(5),
            ]);
        }

        // ── Oxytocin — expiring in 40 days ───────────────────────────
        $oxytocin = Drug::where('name', 'Oxytocin')->first();
        if ($oxytocin) {
            $createBatch([
                'drug_id'            => $oxytocin->id,
                'supplier_id'        => $nms?->id,
                'batch_number'       => 'NMS-OXT-2023-018',
                'quantity_received'  => 60,
                'quantity_remaining' => 22,
                'manufacture_date'   => $today->copy()->subMonths(20),
                'expiry_date'        => $today->copy()->addDays(40),   // near-expiry warning
                'received_date'      => $today->copy()->subMonths(8),
            ]);
        }

        // ── Salbutamol Inhaler ────────────────────────────────────────
        $salbutamol = Drug::where('name', 'Salbutamol')->first();
        if ($salbutamol) {
            $createBatch([
                'drug_id'            => $salbutamol->id,
                'supplier_id'        => $jms?->id,
                'batch_number'       => 'JMS-SAL-2024-003',
                'quantity_received'  => 40,
                'quantity_remaining' => 28,
                'manufacture_date'   => $today->copy()->subMonths(4),
                'expiry_date'        => $today->copy()->addMonths(16),
                'received_date'      => $today->copy()->subMonths(2),
            ]);
        }

        // ── Ciprofloxacin ─────────────────────────────────────────────
        $cipro = Drug::where('name', 'Ciprofloxacin')->first();
        if ($cipro) {
            $createBatch([
                'drug_id'            => $cipro->id,
                'supplier_id'        => $jms?->id,
                'batch_number'       => 'JMS-CIP-2024-009',
                'quantity_received'  => 300,
                'quantity_remaining' => 170,
                'manufacture_date'   => $today->copy()->subMonths(3),
                'expiry_date'        => $today->copy()->addMonths(20),
                'received_date'      => $today->copy()->subMonths(2),
            ]);
        }

        // ── Omeprazole ────────────────────────────────────────────────
        $ome = Drug::where('name', 'Omeprazole')->first();
        if ($ome) {
            $createBatch([
                'drug_id'            => $ome->id,
                'supplier_id'        => $jms?->id,
                'batch_number'       => 'JMS-OME-2024-012',
                'quantity_received'  => 200,
                'quantity_remaining' => 145,
                'manufacture_date'   => $today->copy()->subMonths(2),
                'expiry_date'        => $today->copy()->addMonths(22),
                'received_date'      => $today->copy()->subMonths(1),
            ]);
        }
    }
}
