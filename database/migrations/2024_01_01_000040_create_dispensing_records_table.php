<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispensing_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('dispensed_by')->constrained('users')->onDelete('cascade');
            $table->string('patient_reference');             // OPD number / prescription ID
            $table->string('patient_name')->nullable();
            $table->integer('quantity_dispensed');
            $table->date('dispensed_date');
            $table->time('dispensed_time')->nullable();
            $table->string('diagnosis')->nullable();         // optional clinical note
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispensing_records');
    }
};
