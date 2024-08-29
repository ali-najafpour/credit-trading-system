<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Traits\OrderByIdTrait;
use App\Traits\BasicModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Constraints\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory, BasicModelTrait, SearchableTrait, OrderByIdTrait;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'cart_id',
        'product_id',
        'count'
    ];

    protected $searchable = [
        'product_id',
    ];

    public static function getTableName()
    {
        return 'cart_items';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
