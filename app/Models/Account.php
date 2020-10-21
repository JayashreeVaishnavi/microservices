<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    const REQUEST_ONLY_INPUT = ['bank_name', 'account_number', 'amount'];
    const REQUEST_ONLY_INPUT_FOR_UPDATE = ['bank_name', 'account_number', 'amount', 'id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'bank_name', 'account_number', 'amount',
    ];
}
