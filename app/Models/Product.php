<?php

namespace App\Models;

use App\Traits\OrderByIdTrait;
use App\Traits\BasicModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constraints\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, BasicModelTrait, SearchableTrait, OrderByIdTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
        'visible_in_store',
        'total_count',
        'created_by',
    ];

    protected $searchable = [
        'name',
        'is_active',
        'visible_in_store',
        'created_by',
    ];

    public static function getTableName()
    {
        return 'products';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive()
    {
        return (bool) ($this->attributes['is_active'] == 1);
    }

    public function isVisible()
    {
        return (bool) ($this->attributes['visible_in_store'] == 1);
    }

    public function ScopeIsActive($query)
    {
        $query->where('is_active', 1);
    }

    public function ScopeIsVisible($query)
    {
        $query->where('visible_in_store', 1);
    }

}
