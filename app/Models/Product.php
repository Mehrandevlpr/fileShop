<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $guarded =[];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function owner() {
        return $this->belongsTo(User::class);
    }
    public function payment() {
        return $this->hasMany(Payment::class);
    }
}
