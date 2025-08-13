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
        "order_due", "photo_path", "attachment_path", "data", "status", "version", "current_reviewer_id"
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
        "version" => "integer",
    ];

    /**
     * Статусы договоров
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_ROP = 'pending_rop';
    const STATUS_PENDING_ACCOUNTANT = 'pending_accountant';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ON_HOLD = 'on_hold';

    /**
     * Финансовые поля, которые требуют повторного одобрения
     */
    const FINANCIAL_FIELDS = [
        'order_total',
        'order_deposit', 
        'order_remainder',
        'order_due',
        'payment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Отношение к текущему рецензенту
     */
    public function currentReviewer()
    {
        return $this->belongsTo(User::class, 'current_reviewer_id');
    }

    /**
     * Отношение к изменениям договора
     */
    public function changes()
    {
        return $this->hasMany(ContractChange::class);
    }

    /**
     * Отношение к одобрениям
     */
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Получить последнее одобрение
     */
    public function latestApproval()
    {
        return $this->hasOne(Approval::class)->latest();
    }

    /**
     * Проверить, можно ли редактировать договор
     */
    public function canEdit(User $user = null)
    {
        if (!$user) {
            return false;
        }

        // Админ может редактировать всегда
        if ($user->role === 'admin') {
            return true;
        }

        // Если договор одобрен, только админ может редактировать
        if ($this->status === self::STATUS_APPROVED) {
            return false;
        }

        // Менеджер может редактировать только свои черновики или возвращенные на доработку
        if ($user->role === 'manager') {
            return $this->user_id === $user->id && 
                   in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
        }

        // РОП может редактировать договоры в статусе pending_rop или возвращенные бухгалтером
        if ($user->role === 'rop') {
            return in_array($this->status, [self::STATUS_PENDING_ROP, self::STATUS_REJECTED]);
        }

        // Бухгалтер не может редактировать поля, только одобрять/отклонять
        if ($user->role === 'accountant') {
            return false;
        }

        return false;
    }

    /**
     * Проверить, можно ли выполнить действие workflow
     */
    public function canPerformAction(string $action, User $user = null)
    {
        if (!$user) {
            return false;
        }

        switch ($action) {
            case 'submit_to_rop':
                return $user->role === 'manager' && 
                       $this->status === self::STATUS_DRAFT &&
                       $this->user_id === $user->id;

            case 'submit_to_accountant':
                return $user->role === 'rop' && 
                       $this->status === self::STATUS_PENDING_ROP;

            case 'approve':
                return $user->role === 'accountant' && 
                       $this->status === self::STATUS_PENDING_ACCOUNTANT;

            case 'reject':
            case 'hold':
            case 'return':
                return $user->role === 'accountant' && 
                       $this->status === self::STATUS_PENDING_ACCOUNTANT;

            default:
                return false;
        }
    }

    /**
     * Получить статус для отображения
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_PENDING_ROP => 'На рассмотрении РОП',
            self::STATUS_PENDING_ACCOUNTANT => 'На рассмотрении бухгалтера',
            self::STATUS_APPROVED => 'Одобрен',
            self::STATUS_REJECTED => 'Отклонен',
            self::STATUS_ON_HOLD => 'Приостановлен',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Получить цвет статуса
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_PENDING_ROP => 'warning',
            self::STATUS_PENDING_ACCOUNTANT => 'info',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_ON_HOLD => 'dark',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Проверить, является ли поле финансовым
     */
    public static function isFinancialField(string $field): bool
    {
        return in_array($field, self::FINANCIAL_FIELDS);
    }
}
