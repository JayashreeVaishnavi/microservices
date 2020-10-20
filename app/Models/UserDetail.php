<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use HasFactory, SoftDeletes;

    const REQUEST_ONLY_INPUT = ['first_name', 'last_name', 'email', 'phone_number', 'address',];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_number', 'address',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * User has many accounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class, 'detail_id');
    }

    /**
     * Get the sum of all amounts
     *
     * @return mixed
     */
    public function total_amount()
    {
        return $this->accounts->sum('amount');
    }
}
