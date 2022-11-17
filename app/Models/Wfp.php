<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wfp extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'function_type',
        'deliverables',
        'activities',
        'timeframe_from',
        'timeframe_to',
        'target_q1',
        'target_q2',
        'target_q3',
        'target_q4',
        'item',
        'cost',
        'fund_source_id',
        'responsible_person_id',
        'status',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->code = 'wfp'.'-'.date('Ymd').'-'.bin2hex(random_bytes(2));
            $model->status = 'for eval:l1';
            $model->last_modified_by_id = auth()->id();
        });
        
        static::updating(function ($model) {
            $model->last_modified_by_id = auth()->id();
        });
    }
    
    public function fundSource()
    {
        return $this->belongsTo(FundSource::class);
    }
    
    public function evaluations()
    {
        return $this->hasMany(WfpEvaluation::class);
    }
    
    public function ppmps()
    {
        return $this->hasMany(Ppmp::class);
    }
}
