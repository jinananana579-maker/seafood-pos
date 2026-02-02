<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // 🔥 កំណត់ឈ្មោះ Field ដែលអនុញ្ញាត (ត្រូវមាន unit_price)
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price', // ✅ ត្រូវប្រាកដថាប្រើឈ្មោះនេះ (មិនមែន price ទេ)
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}