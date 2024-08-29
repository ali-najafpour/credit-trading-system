<?php

namespace App\Models;

use App\Models\User;
use App\Traits\OrderByIdTrait;
use App\Traits\BasicModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Constraints\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory, BasicModelTrait, SearchableTrait, OrderByIdTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status',
    ];

    protected $searchable = [
        'status',
    ];

    public static function getTableName()
    {
        return 'carts';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->HasMany(CartItem::class);
    }

    public function isPending()
    {
        return (bool) ($this->attributes['status'] == "pending");
    }

    public function isCompleted()
    {
        return (bool) ($this->attributes['status'] == "completed");
    }

    public function ScopeIsPending($query)
    {
        $query->where('status', 'pending');
    }

    public function ScopeIsCompleted($query)
    {
        $query->where('status', 'completed');
    }


}
