<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The ingredients that belong to the product.
     * 
     * @return BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');
    }

    /**
     * The orders that belong to the product.
     * 
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }
}
