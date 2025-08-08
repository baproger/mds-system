<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ["name", "code", "contract_counter"];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function salesStaff()
    {
        return $this->users()->whereIn('role', ['manager', 'rop']);
    }

    public function managers()
    {
        return $this->users()->where('role', 'manager');
    }

    public function rops()
    {
        return $this->users()->where('role', 'rop');
    }

    public function rop()
    {
        return $this->users()->where('role', 'rop');
    }

    public function getSalesStaffCountAttribute()
    {
        return $this->users()->whereIn('role', ['manager', 'rop'])->count();
    }

    public function getManagersCountAttribute()
    {
        return $this->users()->where('role', 'manager')->count();
    }

    public function getRopCountAttribute()
    {
        return $this->users()->where('role', 'rop')->count();
    }

    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function getContractsCountAttribute()
    {
        return $this->contracts()->count();
    }

    public function getContractsThisMonthAttribute()
    {
        return $this->contracts()->whereMonth('created_at', now()->month)->count();
    }

    public function getTotalRevenueAttribute()
    {
        return $this->contracts()->sum('order_total');
    }

    public function getRevenueThisMonthAttribute()
    {
        return $this->contracts()->whereMonth('created_at', now()->month)->sum('order_total');
    }
}
