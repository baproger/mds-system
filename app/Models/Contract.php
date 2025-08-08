<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        "contract_number", "user_id", "manager", "branch_id", "client", "instagram", "iin", "phone", "phone2",
        "address", "payment", "date", "category", "model", "width", "height", "design", "leaf",
        "framugawidth", "framugaheight", "forging", "opening", "frame", "outer_panel", "outer_cover",
        "outer_cover_color", "metal_cover_hidden", "metal_cover_color", "inner_trim", "inner_cover",
        "inner_trim_color", "glass_unit", "extra", "lock", "handle", "steel_thickness", "canvas_thickness",
        "measurement", "delivery", "installation", "order_total", "order_deposit", "order_remainder",
        "order_due", "photo_path", "attachment_path", "data"
    ];

    protected $casts = [
        "date" => "date",
        "data" => "array",
        "width" => "decimal:2",
        "height" => "decimal:2",
        "order_total" => "decimal:2",
        "order_deposit" => "decimal:2",
        "order_remainder" => "decimal:2",
        "order_due" => "decimal:2",
        "steel_thickness" => "decimal:1",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
