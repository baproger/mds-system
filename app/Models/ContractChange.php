<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'role',
        'field',
        'old_value',
        'new_value',
        'version_from',
        'version_to',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
        'version_from' => 'integer',
        'version_to' => 'integer',
    ];

    /**
     * Отношение к договору
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Отношение к пользователю
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить человекочитаемое название поля
     */
    public function getFieldLabelAttribute()
    {
        $labels = [
            'contract_number' => 'Номер договора',
            'client' => 'Клиент',
            'phone' => 'Телефон',
            'phone2' => 'Доп. телефон',
            'iin' => 'ИИН',
            'manager' => 'Менеджер',
            'order_total' => 'Общая сумма',
            'deposit' => 'Депозит',
            'remainder' => 'Остаток',
            'due_date' => 'Дата оплаты',
            'payment_method' => 'Способ оплаты',
            'status' => 'Статус',
        ];

        return $labels[$this->field] ?? $this->field;
    }

    /**
     * Получить форматированное значение
     */
    public function getFormattedOldValueAttribute()
    {
        return $this->formatValue($this->old_value);
    }

    public function getFormattedNewValueAttribute()
    {
        return $this->formatValue($this->new_value);
    }

    private function formatValue($value)
    {
        if ($value === null) {
            return '—';
        }

        // Форматирование для денежных полей
        if (in_array($this->field, ['order_total', 'deposit', 'remainder'])) {
            return number_format($value, 0, '.', ' ') . ' ₸';
        }

        // Форматирование для дат
        if (in_array($this->field, ['due_date', 'date'])) {
            return \Carbon\Carbon::parse($value)->format('d.m.Y');
        }

        return $value;
    }
}
