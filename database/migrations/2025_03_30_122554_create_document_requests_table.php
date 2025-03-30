<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
            $table->foreignId('document_type_id')->constrained()->onDelete('cascade'); // Link to document_types table
            $table->json('details')->nullable(); // Store specific form data (like business name, property ID, etc.)
            $table->string('status')->default('Pending Payment'); // e.g., Pending Payment, Processing, Ready for Pickup, Completed, Rejected
            $table->string('payment_method')->nullable(); // e.g., 'gcash', 'maya'
            $table->string('payment_reference')->nullable()->unique(); // Reference from GCash/Maya
            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('admin_notes')->nullable(); // Notes from admin (e.g., reason for rejection)
            $table->timestamps(); // requested_at (created_at), updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
