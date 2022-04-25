<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    public static $wrap = null;
    protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'contact',
    'region',
    'course',
    'fee',
    'transaction_id',
    'national_identity',
    'batch'
    ];
}
