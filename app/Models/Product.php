<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'price',
        'stock',
        'unit',
        'category',
    ];

    // 🔥 1. បន្ថែមបន្ទាត់នេះ (ដើម្បីឱ្យវាផ្ញើ image_url ទៅ JSON ពេល Edit)
    protected $appends = ['image_url'];

    // 🔥 2. បង្កើត Function នេះដើម្បីចាប់យក Link រូបភាពឱ្យត្រូវគ្រប់កាលៈទេសៈ
    public function getImageUrlAttribute()
    {
        // បើគ្មានរូប -> ដាក់រូប Placeholder
        if (empty($this->image)) {
            return 'https://placehold.co/400x400?text=No+Image';
        }

        // បើជារូប Link ស្រាប់ (http...)
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // បើជារូបចាស់ (មានពាក្យ uploads)
        if (strpos($this->image, 'uploads') !== false) {
            return asset($this->image);
        }

        // បើជារូបថ្មី (Storage) -> ថែម storage/ ពីមុខ
        return asset('storage/' . $this->image);
    }
}