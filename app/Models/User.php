<?php
/**
 * Author: Jun Chen
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'points',
        'address',
    ];

    protected $attributes = [
        'points' => 0,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get all the winners for the User
     *
     * @return HasMany
     */
    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class);
    }
}
