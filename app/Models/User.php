<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'branch_id',
        'role',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'is_active' => 'boolean',
        ];
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeManager($query)
    {
        return $query->where('role', 'manager');
    }

    public function scopeRop($query)
    {
        return $query->where('role', 'rop');
    }

    public function scopeSalesStaff($query)
    {
        return $query->whereIn('role', ['manager', 'rop']);
    }

    public function scopeNonSalesStaff($query)
    {
        return $query->whereNotIn('role', ['manager', 'rop']);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function getManagersCountAttribute()
    {
        return User::where('branch_id', $this->branch_id)
            ->where('role', 'manager')
            ->count();
    }

    public function getRopCountAttribute()
    {
        return User::where('branch_id', $this->branch_id)
            ->where('role', 'rop')
            ->count();
    }

    public function getSalesStaffCountAttribute()
    {
        return User::where('branch_id', $this->branch_id)
            ->whereIn('role', ['manager', 'rop'])
            ->count();
    }
}
