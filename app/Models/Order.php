<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 🔥 កំណត់ឈ្មោះ Field ដែលអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យបាន
    protected $fillable = [
        'user_id',
        'total_price',
        'received_amount',
        'change_amount', // ✅ ត្រូវតែមានកន្លែងនេះ ទើបកត់ត្រាប្រាក់អាប់បាន
        'payment_method',
        'created_at' // បើចង់កំណត់ម៉ោងលក់ផ្ទាល់ខ្លួន
    ];

    // ទំនាក់ទំនងទៅ OrderItem (Order មួយ មានទំនិញច្រើន)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ទំនាក់ទំនងទៅ User (Order មួយ ត្រូវលក់ដោយបុគ្គលិកម្នាក់)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}