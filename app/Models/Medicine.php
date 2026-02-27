<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function isLowStockAttribute()
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function isExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
