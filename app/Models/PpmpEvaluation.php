<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpmpEvaluation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ppmp_id',
        'evaluation',
        'remarks',
        'evaluated_by_id',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->evaluated_by_id = auth()->id();
        });
    }
    
    public function ppmp()
    {
        return $this->belongsTo(Ppmp::class);
    }
}
