<?php

namespace App\Models;

use App\Enums\LogState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state',
        'price',
        'payment_id',
    ];

    /**
     * Castable attributes.$cast
     *
     * @var array<string, string>
     */
    protected $cast = [
        'state' => LogState::class,
        'price' => 'integer',
    ];

    /**
     * Returns the car that owns the log.
     *
     * @return BelongsTo<Car>
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
