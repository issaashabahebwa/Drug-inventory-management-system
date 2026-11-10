<?php

namespace Database\Seeders;

use App\Models\Drug;
use Illuminate\Database\Seeder;

class DrugSeeder extends Seeder
{
    public function run(): void
    {
        $drugs = [
            // ── Analgesics & Anti-Pyretics ──────────────────────────────
            [
                'name'            => 'Paracetamol',
                'brand_name'      => 'Panadol',
                'category'        => 'Analgesics & Anti-Pyretics',
                'formulation'     => 'tablet',
                'strength'        => '500mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 500,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Paracetamol Syrup',
                'brand_name'      => 'Calpol',
                'category'        => 'Analgesics & Anti-Pyretics',
                'formulation'     => 'syrup',
                'strength'        => '120mg/5ml',
                'unit_of_measure' => 'bottles',
                'reorder_level'   => 100,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Ibuprofen',
                'brand_name'      => null,
                'category'        => 'Analgesics & Anti-Pyretics',
                'formulation'     => 'tablet',
                'strength'        => '200mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 300,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Diclofenac Sodium',
                'brand_name'      => 'Voltaren',
                'category'        => 'Analgesics & Anti-Pyretics',
                'formulation'     => 'injection',
                'strength'        => '75mg/3ml',
                'unit_of_measure' => 'vials',
                'reorder_level'   => 50,
                'total_quantity'  => 0,
            ],

            // ── Antibiotics ─────────────────────────────────────────────
            [
                'name'            => 'Amoxicillin',
                'brand_name'      => null,
                'category'        => 'Antibiotics & Anti-Infectives',
                'formulation'     => 'capsule',
                'strength'        => '250mg',
                'unit_of_measure' => 'capsules',
                'reorder_level'   => 400,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Amoxicillin Syrup',
                'brand_name'      => null,
                'category'        => 'Antibiotics & Anti-Infectives',
                'formulation'     => 'syrup',
                'strength'        => '125mg/5ml',
                'unit_of_measure' => 'bottles',
                'reorder_level'   => 80,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Co-trimoxazole',
                'brand_name'      => 'Septrin',
                'category'        => 'Antibiotics & Anti-Infectives',
                'formulation'     => 'tablet',
                'strength'        => '480mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 300,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Metronidazole',
                'brand_name'      => 'Flagyl',
                'category'        => 'Antibiotics & Anti-Infectives',
                'formulation'     => 'tablet',
                'strength'        => '400mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 200,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Ciprofloxacin',
                'brand_name'      => null,
                'category'        => 'Antibiotics & Anti-Infectives',
                'formulation'     => 'tablet',
                'strength'        => '500mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 150,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Gentamicin',
                'brand_name'      => null,
                'category'        => 'Antibiotics & Anti-Infectives',
                'formulation'     => 'injection',
                'strength'        => '80mg/2ml',
                'unit_of_measure' => 'vials',
                'reorder_level'   => 30,
                'total_quantity'  => 0,
            ],

            // ── Antimalarials ───────────────────────────────────────────
            [
                'name'            => 'Artemether-Lumefantrine (AL)',
                'brand_name'      => 'Coartem',
                'category'        => 'Antimalarials',
                'formulation'     => 'tablet',
                'strength'        => '20mg/120mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 300,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Quinine Sulphate',
                'brand_name'      => null,
                'category'        => 'Antimalarials',
                'formulation'     => 'tablet',
                'strength'        => '300mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 100,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Quinine Injection',
                'brand_name'      => null,
                'category'        => 'Antimalarials',
                'formulation'     => 'injection',
                'strength'        => '300mg/ml',
                'unit_of_measure' => 'vials',
                'reorder_level'   => 20,
                'total_quantity'  => 0,
            ],

            // ── ARVs & Antivirals ────────────────────────────────────────
            [
                'name'            => 'Lamivudine + Tenofovir + Dolutegravir',
                'brand_name'      => 'TLD',
                'category'        => 'Antivirals & ARVs',
                'formulation'     => 'tablet',
                'strength'        => '300/300/50mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 200,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Nevirapine Syrup',
                'brand_name'      => null,
                'category'        => 'Antivirals & ARVs',
                'formulation'     => 'syrup',
                'strength'        => '50mg/5ml',
                'unit_of_measure' => 'bottles',
                'reorder_level'   => 30,
                'total_quantity'  => 0,
            ],

            // ── Antihypertensives ────────────────────────────────────────
            [
                'name'            => 'Amlodipine',
                'brand_name'      => null,
                'category'        => 'Antihypertensives',
                'formulation'     => 'tablet',
                'strength'        => '5mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 200,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Enalapril',
                'brand_name'      => null,
                'category'        => 'Antihypertensives',
                'formulation'     => 'tablet',
                'strength'        => '10mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 150,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Hydrochlorothiazide',
                'brand_name'      => null,
                'category'        => 'Antihypertensives',
                'formulation'     => 'tablet',
                'strength'        => '25mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 150,
                'total_quantity'  => 0,
            ],

            // ── Antidiabetics ────────────────────────────────────────────
            [
                'name'            => 'Metformin',
                'brand_name'      => 'Glucophage',
                'category'        => 'Antidiabetics',
                'formulation'     => 'tablet',
                'strength'        => '500mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 150,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Glibenclamide',
                'brand_name'      => null,
                'category'        => 'Antidiabetics',
                'formulation'     => 'tablet',
                'strength'        => '5mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 100,
                'total_quantity'  => 0,
            ],

            // ── Vitamins & Nutritional ───────────────────────────────────
            [
                'name'            => 'Folic Acid',
                'brand_name'      => null,
                'category'        => 'Vitamins & Nutritional Supplements',
                'formulation'     => 'tablet',
                'strength'        => '5mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 300,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Ferrous Sulphate',
                'brand_name'      => null,
                'category'        => 'Vitamins & Nutritional Supplements',
                'formulation'     => 'tablet',
                'strength'        => '200mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 300,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Vitamin B Complex',
                'brand_name'      => null,
                'category'        => 'Vitamins & Nutritional Supplements',
                'formulation'     => 'tablet',
                'strength'        => null,
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 200,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Zinc Sulphate',
                'brand_name'      => null,
                'category'        => 'Vitamins & Nutritional Supplements',
                'formulation'     => 'tablet',
                'strength'        => '20mg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 100,
                'total_quantity'  => 0,
            ],

            // ── Gastrointestinal ─────────────────────────────────────────
            [
                'name'            => 'Oral Rehydration Salts (ORS)',
                'brand_name'      => null,
                'category'        => 'Gastrointestinal Drugs',
                'formulation'     => 'other',
                'strength'        => null,
                'unit_of_measure' => 'sachets',
                'reorder_level'   => 200,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Omeprazole',
                'brand_name'      => 'Losec',
                'category'        => 'Gastrointestinal Drugs',
                'formulation'     => 'capsule',
                'strength'        => '20mg',
                'unit_of_measure' => 'capsules',
                'reorder_level'   => 100,
                'total_quantity'  => 0,
            ],

            // ── Obstetric & Gynaecological ───────────────────────────────
            [
                'name'            => 'Oxytocin',
                'brand_name'      => 'Syntocinon',
                'category'        => 'Obstetric & Gynaecological',
                'formulation'     => 'injection',
                'strength'        => '10IU/ml',
                'unit_of_measure' => 'vials',
                'reorder_level'   => 20,
                'total_quantity'  => 0,
            ],
            [
                'name'            => 'Misoprostol',
                'brand_name'      => 'Cytotec',
                'category'        => 'Obstetric & Gynaecological',
                'formulation'     => 'tablet',
                'strength'        => '200mcg',
                'unit_of_measure' => 'tablets',
                'reorder_level'   => 50,
                'total_quantity'  => 0,
            ],

            // ── Antifungals ──────────────────────────────────────────────
            [
                'name'            => 'Fluconazole',
                'brand_name'      => 'Diflucan',
                'category'        => 'Antifungals',
                'formulation'     => 'capsule',
                'strength'        => '150mg',
                'unit_of_measure' => 'capsules',
                'reorder_level'   => 50,
                'total_quantity'  => 0,
            ],

            // ── Respiratory ──────────────────────────────────────────────
            [
                'name'            => 'Salbutamol',
                'brand_name'      => 'Ventolin',
                'category'        => 'Respiratory Drugs',
                'formulation'     => 'inhaler',
                'strength'        => '100mcg/dose',
                'unit_of_measure' => 'inhalers',
                'reorder_level'   => 20,
                'total_quantity'  => 0,
            ],
        ];

        foreach ($drugs as $drug) {
            Drug::updateOrCreate(
                ['name' => $drug['name'], 'strength' => $drug['strength'] ?? null],
                $drug
            );
        }
    }
}
