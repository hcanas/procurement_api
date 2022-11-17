<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * User placeholder.
     * Data will be fetched from portal via custom auth middleware.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'permissions',
    ];
}
