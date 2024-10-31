<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';


    protected $fillable = [
        'contract_sponsor_id',
        'contract_parent_id',
        'network_side',
        'status',
        'network_insertion_method'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'contract_id', 'contract_id');
    }

    public function network_info() 
    { 
        return $this->belongsTo(NetworkInfo::class, 'contract_id');

    }

}
