<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'document_type_id',
        'details',
        'status',
        'payment_method',
        'payment_reference',
        'amount_paid',
        'paid_at',
        'admin_notes',
    ];

    protected $casts = [
        'details' => 'array', // Automatically cast JSON to array
        'paid_at' => 'datetime',
    ];

    // Define relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function documentType() {
        return $this->belongsTo(DocumentType::class);
    }
}
