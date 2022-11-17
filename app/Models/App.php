<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppmp_id',
        'end_user',
        'early_procurement',
        'procurement_mode',
        'advertisement_date',
        'opening_date',
        'noa_date',
        'signing_date',
    ];

    public function ppmp()
    {
        return $this->belongsTo(Ppmp::class);
    }
}
