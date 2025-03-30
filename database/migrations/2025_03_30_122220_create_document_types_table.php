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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g., 'Permits', 'Real Property Tax', 'Ordinances', 'Local Civil Registry', 'Occupation Permit/Health'
            $table->string('name'); // e.g., 'New Business Permit', 'Renewal Business Permit', 'Tax Payment', 'Birth Certificate'
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0.00); // Price for the document
            $table->boolean('is_active')->default(true); // Indicates if the document type is active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
