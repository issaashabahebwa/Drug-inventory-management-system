<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('batch_number');
            $table->integer('quantity_received');
            $table->integer('quantity_remaining');           // decrements as dispensed
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date');
            $table->date('received_date');
            $table->string('delivery_note_number')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->enum('status', ['active', 'depleted', 'expired', 'quarantined'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};
