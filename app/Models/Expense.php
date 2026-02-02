<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'amount', 
        'date', // 🔥 ត្រូវប្រាកដថាជា 'date'
        'description',
        'category' // 👈 ត្រូវតែមានពាក្យនេះ ទើប Save ចូល Database បាន
    ];
}