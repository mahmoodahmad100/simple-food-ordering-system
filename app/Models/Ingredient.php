<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'total_amount',
        'current_amount',
    ];

    /**
     * The products that belong to the ingredient.
     * 
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('amount');
    }
}
