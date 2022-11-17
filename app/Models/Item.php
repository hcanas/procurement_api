<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $fillable = [
        'title',
        'category_id',
        'commodity_type',
        'details',
    ];
    
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }
    
    public function ppmps()
    {
        return $this->hasMany(Ppmp::class);
    }
}
