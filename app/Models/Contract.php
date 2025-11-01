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
    const STATUS_APPROVED = 'approved';              // Одобрен
    const STATUS_REJECTED = 'rejected';              // Отклонен
    const STATUS_READY = 'ready';                    // Готов к отгрузке
    const STATUS_SHIPPED = 'shipped';                // Отгружен
    const STATUS_COMPLETED = 'completed';            // Завершен
    const STATUS_RETURNED = 'returned';              // Возвращен на доработку

    /**
     * Порядок статусов в воронке
     */
    const FUNNEL_ORDER = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_ROP,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_READY,
        self::STATUS_SHIPPED,
        self::STATUS_COMPLETED,
        self::STATUS_RETURNED
    ];

    /**
     * Статусы, которые можно вернуть на доработку
     */
    const REVERSIBLE_STATUSES = [
        self::STATUS_PENDING_ROP,
        self::STATUS_APPROVED
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

        // Production и Accountant - только просмотр (read-only)
        if (in_array($user->role, ['production', 'accountant'])) {
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
            case 'quality_check':
            case 'mark_ready':
                return in_array($user->role, ['admin', 'rop', 'manager']) && 
                       $this->status === self::STATUS_APPROVED;

            case 'ship':
                return in_array($user->role, ['admin', 'rop', 'manager', 'production']) && 
                       $this->status === self::STATUS_READY;

            case 'complete':
                return in_array($user->role, ['admin', 'rop', 'manager', 'production']) && 
                       $this->status === self::STATUS_SHIPPED;

            case 'admin_change_status':
                return $user->role === 'admin';

            case 'production_change_status':
                return $user->role === 'production' && in_array($this->status, [
                    self::STATUS_APPROVED,
                    self::STATUS_READY,
                    self::STATUS_SHIPPED
                ]);

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
     * Получить человекочитаемую метку статуса
     */
    public static function getStatusLabel($status)
    {
        $labels = [
            self::STATUS_DRAFT => 'Новая заявка',
            self::STATUS_PENDING_ROP => 'На рассмотрении',
            self::STATUS_APPROVED => 'Одобрено',
            self::STATUS_REJECTED => 'Отклонено',
            self::STATUS_READY => 'Готово',
            self::STATUS_SHIPPED => 'Отправлено',
            self::STATUS_COMPLETED => 'Завершено',
            self::STATUS_RETURNED => 'На доработке',
        ];

        return $labels[$status] ?? 'Неизвестный статус';
    }

    /**
     * Получить цвет для статуса
     */
    public static function getStatusColor($status)
    {
        $colors = [
            self::STATUS_DRAFT => '#6b7280',
            self::STATUS_PENDING_ROP => '#f59e0b',
            self::STATUS_APPROVED => '#10b981',
            self::STATUS_REJECTED => '#ef4444',
            self::STATUS_READY => '#84cc16',
            self::STATUS_SHIPPED => '#f97316',
            self::STATUS_COMPLETED => '#059669',
            self::STATUS_RETURNED => '#6b7280',
        ];

        return $colors[$status] ?? '#6b7280';
    }

    /**
     * Получить иконку для статуса
     */
    public static function getStatusIcon($status)
    {
        $icons = [
            self::STATUS_DRAFT => 'fas fa-edit',
            self::STATUS_PENDING_ROP => 'fas fa-user-tie',
            self::STATUS_APPROVED => 'fas fa-check-circle',
            self::STATUS_REJECTED => 'fas fa-times-circle',
            self::STATUS_READY => 'fas fa-shipping-fast',
            self::STATUS_SHIPPED => 'fas fa-truck',
            self::STATUS_COMPLETED => 'fas fa-flag-checkered',
            self::STATUS_RETURNED => 'fas fa-undo',
        ];

        return $icons[$status] ?? 'fas fa-circle';
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
    * Получить прогресс воронки в процентах
     */
    public function getFunnelProgressAttribute()
    {
        $status = $this->status;
        $funnelOrder = self::FUNNEL_ORDER;
        
        // Находим позицию текущего статуса в воронке
        $currentPosition = array_search($status, $funnelOrder);
        
        if ($currentPosition === false) {
            return 0; // Статус не найден в воронке
        }
        
        // Исключаем отклоненные и возвращенные статусы из расчета прогресса
        $excludedStatuses = [self::STATUS_REJECTED, self::STATUS_RETURNED];
        $validStatuses = array_filter($funnelOrder, function($status) use ($excludedStatuses) {
            return !in_array($status, $excludedStatuses);
        });
        
        $validStatuses = array_values($validStatuses); // Переиндексируем массив
        $currentValidPosition = array_search($status, $validStatuses);
        
        if ($currentValidPosition === false) {
            return 0;
        }
        
        // Рассчитываем процент прогресса
        $totalSteps = count($validStatuses);
        $progress = (($currentValidPosition + 1) / $totalSteps) * 100;
        
        return round($progress);
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
        $contracts = $query->orderBy('updated_at', 'desc')->get();
        
        // Добавляем вычисляемые атрибуты для каждого договора
        $contracts->each(function ($contract) {
            $contract->append('funnel_progress');
        });
        
        return $contracts->groupBy('status');
    }

    /**
     * Scope для фильтрации договоров по роли пользователя
     */
    public function scopeForRole($query, User $user)
    {
        switch ($user->role) {
            case 'admin':
                // Админ видит все договоры
                return $query;
                
            case 'manager':
                // Менеджер видит только свои договоры
                return $query->where('user_id', $user->id);
                
            case 'rop':
                // РОП видит договоры своего филиала
                return $query->where('branch_id', $user->branch_id);
                
            case 'production':
                // Production видит договоры одобренные и связанных статусах
                return $query->whereIn('status', [
                    self::STATUS_APPROVED,
                    self::STATUS_READY,
                    self::STATUS_SHIPPED
                ]);
                
            case 'accountant':
                // Бухгалтер видит все договоры
                return $query;
                
            default:
                return $query->whereRaw('1 = 0'); // Ничего не показываем
        }
    }

    /**
     * Scope для договоров в производстве
     */
    public function scopeInProduction($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
