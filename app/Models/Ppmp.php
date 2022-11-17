<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppmp extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'wfp_id',
        'item_id',
        'quantity',
        'unit',
        'abc',
        'procurement_mode',
        'milestone_1',
        'milestone_2',
        'milestone_3',
        'milestone_4',
        'milestone_5',
        'milestone_6',
        'milestone_7',
        'milestone_8',
        'milestone_9',
        'milestone_10',
        'milestone_11',
        'milestone_12',
        'status',
    ];
    
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->code = 'ppmp'.'-'.date('Ymd').'-'.bin2hex(random_bytes(2));
            $model->status = 'for eval:l1';
            $model->last_modified_by_id = auth()->id();
        });
        
        static::updating(function ($model) {
            $model->last_modified_by_id = auth()->id();
        });
    }
    
    public function wfp()
    {
        return $this->belongsTo(Wfp::class, 'wfp_id');
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    public function evaluations()
    {
        return $this->hasMany(PpmpEvaluation::class);
    }

    public function apps()
    {
        return $this->hasMany(App::class);
    }
}
