<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkInfo extends Model
{
    use HasFactory;

    protected $table = 'contract_network_infos';
    protected $primaryKey = 'contract_network_infos_id';

    protected $fillable = [
        'contract_id',
        'left_side_size',
        'right_side_size',
        'left_side_directs',
        'right_side_directs'
    ];

}
