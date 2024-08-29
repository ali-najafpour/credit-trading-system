<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\OrderByIdTrait;
use App\Traits\BasicModelTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\QueryException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constraints\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, BasicModelTrait, SearchableTrait, OrderByIdTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'country_code',
        'cell_number',
        'cell_number_verified_at',
        'email',
        'email_verified_at',
        'role'
    ];

    protected $searchable = [
        'first_name',
        'last_name',
        'username',
        'cell_number',
        'email',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cell_number_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getTableName()
    {
        return 'users';
    }

    /**
     * Set the user's verification.
     *
     * @param string $value
     * @return self
     */
    public function verifyPhone()
    {
        $this->cell_number_verified_at = Carbon::now();
        return $this;
    }

    public function verifyEmail()
    {
        $this->email_verified_at = Carbon::now();
        return $this;
    }

    public function isVerified()
    {
        return (!!$this->attributes['email_verified_at']) || (!!$this->attributes['cell_number_verified_at']);
    }

    /**
     * Check the user's phone verified:
     *
     * @return bool
     */
    public function isCellNumberVerified()
    {
        return (bool) $this->attributes['cell_number_verified_at'];
    }

    /**
     * Check the user's email verified:
     *
     * @return bool
     */
    public function isEmailVerified()
    {
        return (bool) $this->attributes['email_verified_at'];
    }

    public function ScopeIsVerified($query)
    {
        $query->whereNotNull('email_verified_at')->orWhereNotNull('cell_number_verified_at');
    }

    public function ScopeIsActive($query)
    {
        $query->where('is_active', 1);
    }

    public function ScopeIsClient($query)
    {
        $query->where('role', 'client');
    }

    public function ScopeIsAdmin($query)
    {
        $query->where('role', 'admin');
    }

    public function ScopeIsManager($query)
    {
        $query->where('role', 'manager');
    }

    public function role()
    {
        return $this->role;
    }

    public function hasRole($role)
    {
        return (bool) ($this->attributes['role'] == $role);
    }

}
