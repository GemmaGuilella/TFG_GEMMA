<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'price_hour',
        'token_expiration',
    ];

    /**
     * Castable attributes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_hour' => 'integer',
        'token_expiration' => 'integer',
    ];

    /**
     * Returns the price in minutes, ceiled.
     *
     * @return float
     */
    public function priceMinute(): float
    {
        return $this->price_hour / 60;
    }
}
