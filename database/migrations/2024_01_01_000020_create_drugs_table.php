<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                // Generic drug name
            $table->string('brand_name')->nullable();
            $table->string('category');                           // e.g. Analgesics, Antibiotics, etc.
            $table->string('formulation');                        // tablet | syrup | injection | capsule | cream | drops | other
            $table->string('strength')->nullable();               // e.g. 500mg, 250mg/5ml
            $table->string('unit_of_measure');                    // tablets | bottles | vials | sachets
            $table->integer('reorder_level')->default(50);        // minimum quantity before alert triggers
            $table->integer('total_quantity')->default(0);        // computed/maintained balance
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
