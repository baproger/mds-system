<?php

namespace App\Services;

class ContractCalculationService
{
    private $price = [
        "Lux" => [
            "Англия" => 412698,
            "Империал" => 428571,
            "Бастион / Хайтек" => 444444,
            "Фьюжн / Хайтек" => 388889,
            "Фьюжн сложный" => 476190,
            "Фьюжн HPL" => 476190,
            "Лион" => 365079,
            "Агора" => 301587,
            "Ажур" => 309524,
            "Горизонт" => 301587,
            "Остиум (Армада люкс)" => 349206,
            "Армада" => 365079,
            "Акрополь" => 261905,
            "Эксклюзив" => 603175
        ],
        "Premium" => [
            "Англия" => 365079,
            "Империал" => 380952,
            "Бастион / Хайтек" => 396825,
            "Фьюжн / Хайтек" => 341270,
            "Фьюжн сложный" => 428571,
            "Фьюжн HPL" => 428571,
            "Лион" => 317460,
            "Агора" => 253968,
            "Ажур" => 261905,
            "Горизонт" => 253968,
            "Остиум (Армада люкс)" => 301587,
            "Армада" => 317460,
            "Акрополь" => 214286
        ],
        "Comfort" => [
            "Англия" => 301587,
            "Империал" => 317460,
            "Бастион / Хайтек" => 333333,
            "Фьюжн / Хайтек" => 301587,
            "Лион" => 253968,
            "Агора" => 190476,
            "Ажур" => 198413,
            "Горизонт" => 190476,
            "Остиум (Армада люкс)" => 238095,
            "Армада" => 253968,
            "Акрополь" => 158730
        ]
    ];

    private $fusionModels = ["Фьюжн / Хайтек", "Фьюжн сложный", "Фьюжн HPL"];

    public function getPrice()
    {
        return $this->price;
    }

    public function getFusionModels()
    {
        return $this->fusionModels;
    }

    public function getModelsByCategory($category)
    {
        return $this->price[$category] ?? [];
    }

    public function getPriceByCategoryAndModel($category, $model)
    {
        return $this->price[$category][$model] ?? null;
    }

    public function isFusionModel($model)
    {
        return in_array($model, $this->fusionModels);
    }

    public function getCanvasThickness($category, $model)
    {
        $baseThickness = [
            "Lux" => 115,
            "Premium" => 100,
            "Comfort" => 90
        ];

        $base = $baseThickness[$category] ?? 80;
        
        if ($this->isFusionModel($model)) {
            $base += 10;
        }

        return $base;
    }

    public function getSteelThickness($category)
    {
        return [
            "Lux" => "1.8",
            "Premium" => "1.5",
            "Comfort" => "1.4"
        ][$category] ?? "1.4";
    }

    public function getOuterPanel($category, $model)
    {
        if ($this->isFusionModel($model)) {
            return "МДФ";
        }

        return [
            "Lux" => "Оцинкованная сталь",
            "Premium" => "Оцинкованная сталь", 
            "Comfort" => "Холоднокатанная сталь"
        ][$category] ?? "Холоднокатанная сталь";
    }

    public function getOuterCoverOptions($category, $model)
    {
        if ($this->isFusionModel($model)) {
            return ["МДФ-эмаль", "Шпон-марилька"];
        }

        return [
            "Lux" => ["Оцинкованная сталь"],
            "Premium" => ["Оцинкованная сталь"],
            "Comfort" => ["Холоднокатанная сталь"]
        ][$category] ?? ["Холоднокатанная сталь"];
    }

    public function getInnerTrimOptions($category)
    {
        return [
            "Lux" => ["МДФ", "металл", "шпон"],
            "Premium" => ["МДФ", "металл", "шпон (за доплату)"],
            "Comfort" => ["МДФ", "металл"]
        ][$category] ?? ["МДФ", "металл"];
    }

    public function getInnerCoverOptions($category)
    {
        return [
            "Lux" => ["Марилька", "Эмаль", "ПВХ пленка"],
            "Premium" => ["Эмаль", "ПВХ пленка", "Шпон-марилька (за доплату)"],
            "Comfort" => ["металл", "Эмаль (за доплату)"]
        ][$category] ?? ["металл"];
    }

    public function getGlassOptions($category)
    {
        return [
            "Lux" => ["синий зеркальный (калёный)", "йодовый зеркальный (калёный)", "йодовый (калёный)", "прозрачный (калёный)"],
            "Premium" => ["синий зеркальный (некалёный)", "йодовый зеркальный (некалёный)", "йодовый (некалёный)", "прозрачный (некалёный)", "синий зеркальный (калёный)", "йодовый зеркальный (калёный)", "йодовый (калёный)", "прозрачный (калёный)"],
            "Comfort" => ["синий зеркальный (некалёный)", "йодовый зеркальный (некалёный)", "йодовый (некалёный)", "прозрачный (некалёный)"]
        ][$category] ?? ["прозрачный (некалёный)"];
    }

    public function getLockOptions($category)
    {
        return [
            "Lux" => ["Kale", "Guardian"],
            "Premium" => ["Kale", "Guardian", "Galeon"],
            "Comfort" => ["Argus", "Galeon"]
        ][$category] ?? ["Argus"];
    }

    public function validateContract($data)
    {
        $errors = [];

        // Обязательные поля
        $required = [
            'contract_number' => 'Номер договора',
            'manager' => 'Менеджер',
            'client' => 'ФИО клиента',
            'instagram' => 'Instagram',
            'iin' => 'ИИН клиента',
            'phone' => 'Телефон',
            'phone2' => 'Доп. телефон',
            'date' => 'Дата договора',
            'category' => 'Категория',
            'model' => 'Модель',
            'width' => 'Ширина',
            'height' => 'Высота',
            'framugawidth' => 'Фрамуга боковая',
            'framugaheight' => 'Фрамуга верхняя',
            'leaf' => 'Створка',
            'glass_unit' => 'Стеклопакет',
            'handle' => 'Ручка',
            'order_total' => 'Общая стоимость заказа',
            'order_deposit' => 'Предоплата',
            'order_remainder' => 'Остаток предоплаты',
            'order_due' => 'К оплате после изготовления',
            'metal_cover_hidden' => 'Покрытие металла',
            'outer_cover' => 'Покрытие наружной панели'
        ];

        foreach ($required as $field => $label) {
            if (in_array($field, ['order_deposit', 'order_remainder', 'order_due'])) {
                if (!isset($data[$field])) {
                    $errors[] = "Поле «{$label}» обязательно.";
                }
            } else {
                if (empty($data[$field])) {
                    $errors[] = "Поле «{$label}» обязательно.";
                }
            }
        }

        // Валидация размеров
        if (!empty($data['width']) && (!is_numeric($data['width']) || $data['width'] < 850)) {
            $errors[] = 'Ширина двери должна быть не менее 850 мм.';
        }

        if (!empty($data['height']) && (!is_numeric($data['height']) || $data['height'] < 850)) {
            $errors[] = 'Высота двери должна быть не менее 850 мм.';
        }

        // Валидация числовых полей
        foreach (['order_total', 'order_deposit', 'order_remainder', 'order_due'] as $numField) {
            if (isset($data[$numField]) && (!is_numeric($data[$numField]) || $data[$numField] < 0)) {
                $errors[] = "Поле «{$required[$numField]}» должно быть ≥ 0.";
            }
        }

        // Валидация ИИН
        if (!empty($data['iin']) && !preg_match('/^\d{12}$/', $data['iin'])) {
            $errors[] = 'ИИН клиента должен состоять ровно из 12 цифр.';
        }

        // Валидация категории и модели
        if (!empty($data['category']) && !isset($this->price[$data['category']])) {
            $errors[] = 'Неверная категория.';
        }

        if (!empty($data['category']) && !empty($data['model']) && !isset($this->price[$data['category']][$data['model']])) {
            $errors[] = 'Неверная модель для выбранной категории.';
        }

        // Fusion: ограничения покрытий
        if (!empty($data['model']) && $this->isFusionModel($data['model'])) {
            if (!empty($data['outer_cover']) && !in_array($data['outer_cover'], ["МДФ-эмаль", "Шпон-марилька"])) {
                $errors[] = 'Для Fusion-модели выберите "МДФ-эмаль" или "Шпон-марилька"!';
            }
        }

        return $errors;
    }

    public function calculateExtra($data)
    {
        $extra = [];

        // Внутренняя обшивка — доплата
        if ($data['category'] === "Premium" && !empty($data['inner_trim']) && stripos($data['inner_trim'], 'шпон') !== false) {
            $extra[] = "внутренняя обшивка - шпон (доплата)";
        }

        if ($data['category'] === "Comfort" && !empty($data['inner_cover']) && stripos($data['inner_cover'], 'эмаль') !== false) {
            $extra[] = "внутренняя обшивка - Эмаль (доплата)";
        }

        if ($data['category'] === "Premium" && !empty($data['inner_cover']) && stripos($data['inner_cover'], 'шпон-марилька') !== false) {
            $extra[] = "внутренняя обшивка - Шпон-марилька (доплата)";
        }

        return implode('; ', $extra);
    }
}
