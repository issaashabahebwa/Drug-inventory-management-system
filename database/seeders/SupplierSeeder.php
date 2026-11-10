<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'National Medical Stores (NMS)',
                'code'           => 'NMS-001',
                'contact_person' => 'Regional Distribution Officer',
                'phone'          => '+256 414 220 027',
                'email'          => 'info@nms.go.ug',
                'address'        => 'Plot 4-6, Nsamizi Road, Entebbe, Uganda',
                'is_active'      => true,
            ],
            [
                'name'           => 'Joint Medical Store (JMS)',
                'code'           => 'JMS-001',
                'contact_person' => 'Sales Representative',
                'phone'          => '+256 414 221 568',
                'email'          => 'info@jms.co.ug',
                'address'        => 'Plot 1A, Kyaggwe Road, Kampala, Uganda',
                'is_active'      => true,
            ],
            [
                'name'           => 'MEDS Uganda',
                'code'           => 'MEDS-001',
                'contact_person' => 'Account Manager',
                'phone'          => '+256 772 100 200',
                'email'          => 'info@medsuganda.org',
                'address'        => 'Kisugu, Kampala, Uganda',
                'is_active'      => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(['code' => $supplier['code']], $supplier);
        }
    }
}
