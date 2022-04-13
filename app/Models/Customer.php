<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'country',
        'service',
        'date',
        'fee',
        'contract_duration',
        'user_id',
        'document',
        'advance',
        'sharing',
        'payment_verified'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
