<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
        'birth_date',
        'email',
        'phone_number'
    ];

    protected $casts = [
        'birth_date' => 'datetime'
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
