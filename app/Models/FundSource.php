<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundSource extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'amount',
        'year',
        'office_id',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->last_modified_by_id = auth()->id();
        });
    }
    
    public function wfps()
    {
        return $this->hasMany(Wfp::class);
    }
}
