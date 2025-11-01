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
        'comment',
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
            'order_deposit' => 'Предоплата',
            'order_remainder' => 'Остаток предоплаты',
            'order_due' => 'К оплате после изготовления',
            'deposit' => 'Депозит',
            'remainder' => 'Остаток',
            'due_date' => 'Дата оплаты',
            'payment_method' => 'Способ оплаты',
            'status' => 'Статус',
            'date' => 'Дата договора',
            'extra' => 'Дополнительные услуги',
            'canvas_thickness' => 'Толщина полотна',
            'steel_thickness' => 'Толщина стали',
            'category' => 'Категория',
            'model' => 'Модель',
            'width' => 'Ширина',
            'height' => 'Высота',
            'leaf' => 'Створка',
            'framugawidth' => 'Фрамуга боковая',
            'framugaheight' => 'Фрамуга верхняя',
            'outer_cover' => 'Наружное покрытие',
            'outer_cover_color' => 'Цвет наружного покрытия',
            'metal_cover_hidden' => 'Покрытие металла',
            'metal_cover_color' => 'Цвет покрытия металла',
            'inner_trim' => 'Внутренняя обшивка',
            'inner_cover' => 'Внутреннее покрытие',
            'inner_trim_color' => 'Цвет внутренней обшивки',
            'glass_unit' => 'Стеклопакет',
            'lock' => 'Замок',
            'handle' => 'Ручка',
            'measurement' => 'Замер',
            'delivery' => 'Доставка',
            'installation' => 'Установка',
            'address' => 'Адрес',
            'instagram' => 'Instagram',
            'payment' => 'Способ оплаты',
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

        // Форматирование для статусов
        if ($this->field === 'status') {
            return $this->getStatusLabel($value);
        }

        // Форматирование для денежных полей
        if (in_array($this->field, ['order_total', 'order_deposit', 'order_remainder', 'order_due', 'deposit', 'remainder'])) {
            return number_format($value, 0, '.', ' ') . ' ₸';
        }

        // Форматирование для дат
        if (in_array($this->field, ['due_date', 'date'])) {
            return \Carbon\Carbon::parse($value)->format('d.m.Y');
        }

        // Форматирование для размеров (добавляем единицы измерения)
        if (in_array($this->field, ['width', 'height', 'canvas_thickness', 'steel_thickness'])) {
            return $value . ' мм';
        }

        return $value;
    }

    /**
     * Получить человекочитаемое название статуса
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Новая заявка',
            'pending_rop' => 'На рассмотрении',
            'approved' => 'Одобрено',
            'rejected' => 'Отклонено',
            'on_hold' => 'Приостановлено',
            'in_production' => 'В работе',
            'quality_check' => 'Проверка',
            'ready' => 'Готово',
            'shipped' => 'Отправлено',
            'completed' => 'Завершено',
            'returned' => 'На доработке',
        ];

        return $labels[$status] ?? $status;
    }
}
