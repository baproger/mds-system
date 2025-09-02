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
     * Статусы договоров - расширенная воронка CRM
     */
    const STATUS_DRAFT = 'draft';                    // Черновик
    const STATUS_PENDING_ROP = 'pending_rop';        // На проверке РОП
    const STATUS_PENDING_ACCOUNTANT = 'pending_accountant'; // На проверке бухгалтера
    const STATUS_APPROVED = 'approved';              // Одобрен
    const STATUS_REJECTED = 'rejected';              // Отклонен
    const STATUS_ON_HOLD = 'on_hold';                // Приостановлен
    const STATUS_IN_PRODUCTION = 'in_production';    // В производстве
    const STATUS_QUALITY_CHECK = 'quality_check';    // Контроль качества
    const STATUS_READY = 'ready';                    // Готов к отгрузке
    const STATUS_SHIPPED = 'shipped';                // Отгружен
    const STATUS_COMPLETED = 'completed';            // Завершен

    /**
     * Порядок статусов в воронке
     */
    const FUNNEL_ORDER = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_ROP,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_ON_HOLD,
        self::STATUS_IN_PRODUCTION,
        self::STATUS_QUALITY_CHECK,
        self::STATUS_READY,
        self::STATUS_SHIPPED,
        self::STATUS_COMPLETED
    ];

    /**
     * Статусы, которые можно вернуть на доработку
     */
    const REVERSIBLE_STATUSES = [
        self::STATUS_PENDING_ROP,
        self::STATUS_APPROVED,
        self::STATUS_IN_PRODUCTION,
        self::STATUS_QUALITY_CHECK
    ];

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
    public function canEdit(?User $user = null)
    {
        if (!$user) {
            return false;
        }

        // Админ может редактировать всегда
        if ($user->role === 'admin') {
            return true;
        }

        // Если договор завершен, только админ может редактировать
        if (in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_SHIPPED])) {
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
    public function canPerformAction(string $action, ?User $user = null)
    {
        if (!$user) {
            return false;
        }

        switch ($action) {
            case 'submit_to_rop':
                return in_array($user->role, ['manager', 'admin']) && 
                       $this->status === self::STATUS_DRAFT &&
                       $this->user_id === $user->id;

            case 'approve':
                return in_array($user->role, ['rop', 'admin']) && 
                       $this->status === self::STATUS_PENDING_ROP;

            case 'reject':
            case 'hold':
            case 'return':
                return in_array($user->role, ['rop', 'admin']) && 
                       $this->status === self::STATUS_PENDING_ROP;

            case 'start_production':
                return in_array($user->role, ['admin', 'rop', 'manager']) && 
                       $this->status === self::STATUS_APPROVED;

            case 'quality_check':
                return in_array($user->role, ['admin', 'rop', 'manager']) && 
                       $this->status === self::STATUS_IN_PRODUCTION;

            case 'mark_ready':
                return in_array($user->role, ['admin', 'rop', 'manager']) && 
                       $this->status === self::STATUS_QUALITY_CHECK;

            case 'ship':
                return in_array($user->role, ['admin', 'rop', 'manager']) && 
                       $this->status === self::STATUS_READY;

            case 'complete':
                return in_array($user->role, ['admin', 'rop', 'manager']) && 
                       $this->status === self::STATUS_SHIPPED;

            case 'admin_change_status':
                return $user->role === 'admin';

            default:
                return false;
        }
    }

    /**
     * Получить статус для отображения
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabel($this->status);
    }

    /**
     * Получить статус для отображения (статический метод)
     */
    public static function getStatusLabel($status)
    {
        $labels = [
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_PENDING_ROP => 'На проверке РОП',
            self::STATUS_PENDING_ACCOUNTANT => 'На проверке бухгалтера',
            self::STATUS_APPROVED => 'Одобрен',
            self::STATUS_REJECTED => 'Отклонен',
            self::STATUS_ON_HOLD => 'Приостановлен',
            self::STATUS_IN_PRODUCTION => 'В производстве',
            self::STATUS_QUALITY_CHECK => 'Контроль качества',
            self::STATUS_READY => 'Готов к отгрузке',
            self::STATUS_SHIPPED => 'Отгружен',
            self::STATUS_COMPLETED => 'Завершен',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Получить цвет статуса
     */
    public function getStatusColorAttribute()
    {
        return self::getStatusColor($this->status);
    }

    /**
     * Получить цвет статуса (статический метод)
     */
    public static function getStatusColor($status)
    {
        $colors = [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_PENDING_ROP => 'warning',
            self::STATUS_PENDING_ACCOUNTANT => 'info',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_ON_HOLD => 'dark',
            self::STATUS_IN_PRODUCTION => 'primary',
            self::STATUS_QUALITY_CHECK => 'info',
            self::STATUS_READY => 'success',
            self::STATUS_SHIPPED => 'warning',
            self::STATUS_COMPLETED => 'success',
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * Получить иконку статуса
     */
    public function getStatusIconAttribute()
    {
        return self::getStatusIcon($this->status);
    }

    /**
     * Получить иконку статуса (статический метод)
     */
    public static function getStatusIcon($status)
    {
        $icons = [
            self::STATUS_DRAFT => 'fas fa-edit',
            self::STATUS_PENDING_ROP => 'fas fa-clock',
            self::STATUS_PENDING_ACCOUNTANT => 'fas fa-calculator',
            self::STATUS_APPROVED => 'fas fa-check-circle',
            self::STATUS_REJECTED => 'fas fa-times-circle',
            self::STATUS_ON_HOLD => 'fas fa-pause-circle',
            self::STATUS_IN_PRODUCTION => 'fas fa-cogs',
            self::STATUS_QUALITY_CHECK => 'fas fa-search',
            self::STATUS_READY => 'fas fa-box',
            self::STATUS_SHIPPED => 'fas fa-truck',
            self::STATUS_COMPLETED => 'fas fa-flag-checkered',
        ];

        return $icons[$status] ?? 'fas fa-file';
    }

    /**
     * Получить следующий статус в воронке
     */
    public function getNextStatus()
    {
        $currentIndex = array_search($this->status, self::FUNNEL_ORDER);
        if ($currentIndex !== false && $currentIndex < count(self::FUNNEL_ORDER) - 1) {
            return self::FUNNEL_ORDER[$currentIndex + 1];
        }
        return null;
    }

    /**
     * Получить предыдущий статус в воронке
     */
    public function getPreviousStatus()
    {
        $currentIndex = array_search($this->status, self::FUNNEL_ORDER);
        if ($currentIndex !== false && $currentIndex > 0) {
            return self::FUNNEL_ORDER[$currentIndex - 1];
        }
        return null;
    }

    /**
     * Проверить, является ли статус финальным
     */
    public function isFinalStatus()
    {
        return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_REJECTED]);
    }

    /**
     * Получить прогресс в воронке (0-100%)
     */
    public function getFunnelProgressAttribute()
    {
        $currentIndex = array_search($this->status, self::FUNNEL_ORDER);
        if ($currentIndex === false) {
            return 0;
        }
        return round(($currentIndex / (count(self::FUNNEL_ORDER) - 1)) * 100);
    }

    /**
     * Проверить, является ли поле финансовым
     */
    public static function isFinancialField(string $field): bool
    {
        return in_array($field, self::FINANCIAL_FIELDS);
    }

    /**
     * Получить статистику по статусам для дашборда
     */
    public static function getStatusStats($branchId = null, $startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();
    }

    /**
     * Получить договоры для канбан-доски
     */
    public static function getKanbanContracts($branchId = null, $userId = null)
    {
        $query = self::with(['user', 'branch', 'currentReviewer']);
        
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Показываем ВСЕ договоры, включая завершенные и отклоненные
        // Это позволит видеть полную картину воронки продаж
        return $query->orderBy('updated_at', 'desc')
                    ->get()
                    ->groupBy('status');
    }
}
