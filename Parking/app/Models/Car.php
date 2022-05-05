<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricula',
        'token',
        'token_created_at',
    ];

    /**
     * Castable attributes
     *
     * @var array<string, string>
     */
    protected $casts = [
        'token_created_at' => 'datetime',
    ];

    /**
     * Retorna l'usuari associat amb el Cotxe.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna la plaça associat amb el Cotxe.
     *
     * @return HasOne
     */
    public function space(): HasOne
    {
        return $this->hasOne(Space::class);
    }

    /**
     * Retorna un bool si aquell cotxe està aparcat
     *
     * @return bool
     */
    public function isParked(): bool
    {
        return $this->space()->exists();
    }

    /**
     * Filtrar els cotxes que estan aparcats
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param bool $value true si vols buscar aparcats
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParked(Builder $query, bool $value = true)
    {
        return $query->whereHas('space', function (Builder $query) use ($value) {
            $query->where('car_id', $value ? '!=' : '=', null);
        });
    }

    /**
     * Retorna els logs dels cotxes.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }
}
