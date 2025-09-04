<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'from_role',
        'to_role',
        'action',
        'comment',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Отношение к договору
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Отношение к пользователю, который создал одобрение
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Получить человекочитаемое название действия
     */
    public function getActionLabelAttribute()
    {
        $labels = [
            'submit' => 'Отправлен',
            'approve' => 'Одобрен',
            'reject' => 'Отклонен',
            'hold' => 'Приостановлен',
            'return' => 'Возвращен на доработку',
            'start_production' => 'Производство начато',
            'quality_check' => 'Отправлен на контроль качества',
            'mark_ready' => 'Готов к отгрузке',
            'ship' => 'Отгружен',
            'complete' => 'Завершен',
        ];

        return $labels[$this->action] ?? $this->action;
    }

    /**
     * Получить цвет для действия
     */
    public function getActionColorAttribute()
    {
        $colors = [
            'submit' => 'primary',
            'approve' => 'success',
            'reject' => 'danger',
            'hold' => 'warning',
            'return' => 'info',
            'start_production' => 'info',
            'quality_check' => 'warning',
            'mark_ready' => 'success',
            'ship' => 'primary',
            'complete' => 'success',
        ];

        return $colors[$this->action] ?? 'secondary';
    }

    /**
     * Получить иконку для действия
     */
    public function getActionIconAttribute()
    {
        $icons = [
            'submit' => 'fas fa-paper-plane',
            'approve' => 'fas fa-check-circle',
            'reject' => 'fas fa-times-circle',
            'hold' => 'fas fa-pause-circle',
            'return' => 'fas fa-undo',
            'start_production' => 'fas fa-cogs',
            'quality_check' => 'fas fa-search',
            'mark_ready' => 'fas fa-check-double',
            'ship' => 'fas fa-shipping-fast',
            'complete' => 'fas fa-flag-checkered',
        ];

        return $icons[$this->action] ?? 'fas fa-circle';
    }

    /**
     * Получить полное описание действия
     */
    public function getFullDescriptionAttribute()
    {
        $fromRole = $this->getRoleLabel($this->from_role);
        $toRole = $this->getRoleLabel($this->to_role);
        $action = $this->action_label;

        return "{$fromRole} → {$toRole}: {$action}";
    }

    private function getRoleLabel($role)
    {
        $labels = [
            'admin' => 'Администратор',
            'manager' => 'Менеджер',
            'rop' => 'РОП',

            'user' => 'Пользователь',
        ];

        return $labels[$role] ?? $role;
    }
}
