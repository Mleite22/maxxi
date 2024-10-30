<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $table = 'network';
    protected $primaryKey = 'network_id';

    protected $fillable = [
        'network_owner_id',
        'contract_id',
        'parent_id',
        'sponsor_id',
        'level',
        'position',
        'absolute_side',
        'relative_side'
    ];

    
}
