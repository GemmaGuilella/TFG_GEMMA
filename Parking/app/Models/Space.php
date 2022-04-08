<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Retorna el cotxe associat a la plaça
     *
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Retorna true si es null
     *
     * @return Bool
     */
    public function isAvailable(): bool
    {
        return $this->car_id === null;
    }

    /**
     * Filtrar les places
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param bool $value true si vols buscar places lliures
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable(Builder $query, bool $value = true)
    {
        return $query->where('car_id', $value ? "=" : "!=", null);
    }
}
