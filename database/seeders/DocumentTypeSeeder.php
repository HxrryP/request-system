<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentType::create(['category' => 'Permits', 'name' => 'New Business Permit', 'price' => 500.00]);
        DocumentType::create(['category' => 'Permits', 'name' => 'Renewal Business Permit', 'price' => 400.00]);
        DocumentType::create(['category' => 'Local Civil Registry', 'name' => 'Birth Certificate', 'price' => 150.00]);
    }
}
