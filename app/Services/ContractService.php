<?php

namespace App\Services;

class ContractService
{
    public static $price = [
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

    public static $fusionModels = ["Фьюжн / Хайтек", "Фьюжн сложный", "Фьюжн HPL"];

    public static $managers = ['Самал', 'Арман', 'Даулет', 'Зухра', 'Фатима', 'Арай', 'Ербол', 'Тоқмұсбек', 'Сымбат', 'Алия', 'Лайла', 'Абылай'];

    public static function getCanvasThickness($category, $model)
    {
        $base = [
            'Lux' => 100,
            'Premium' => 90,
            'Comfort' => 80
        ][$category] ?? 80;

        return in_array($model, self::$fusionModels, true) ? $base + 10 : $base;
    }

    public static function getSteelThickness($category)
    {
        return [
            'Lux' => '1.8',
            'Premium' => '1.5',
            'Comfort' => '1.4'
        ][$category] ?? '1.4';
    }

    public static function getOuterPanel($category, $model)
    {
        $isFusion = in_array($model, self::$fusionModels, true);
        return $isFusion ? 'МДФ' : [
            'Lux' => 'Оцинкованная сталь',
            'Premium' => 'Оцинкованная сталь',
            'Comfort' => 'Холоднокатанная сталь'
        ][$category] ?? 'Холоднокатанная сталь';
    }

    public static function getOuterCoverOptions($category, $model)
    {
        $isFusion = in_array($model, self::$fusionModels, true);
        if ($isFusion) {
            return ['МДФ-эмаль', 'Шпон-марилька'];
        }
        return [
            'Lux' => ['Оцинкованная сталь'],
            'Premium' => ['Оцинкованная сталь'],
            'Comfort' => ['Холоднокатанная сталь']
        ][$category] ?? ['Холоднокатанная сталь'];
    }

    public static function getInnerTrimOptions($category)
    {
        return [
            'Lux' => ['МДФ', 'металл', 'шпон'],
            'Premium' => ['МДФ', 'металл', 'шпон (за доплату)'],
            'Comfort' => ['МДФ', 'металл']
        ][$category] ?? ['МДФ', 'металл'];
    }

    public static function getInnerCoverOptions($category)
    {
        return [
            'Lux' => ['Марилька', 'Эмаль', 'ПВХ пленка'],
            'Premium' => ['Эмаль', 'ПВХ пленка', 'Шпон-марилька (за доплату)'],
            'Comfort' => ['металл', 'Эмаль (за доплату)']
        ][$category] ?? ['металл'];
    }

    public static function getGlassOptions($category)
    {
        return [
            'Lux' => ['синий зеркальный (калёный)', 'йодовый зеркальный (калёный)', 'йодовый (калёный)', 'прозрачный (калёный)'],
            'Premium' => ['синий зеркальный (некалёный)', 'йодовый зеркальный (некалёный)', 'йодовый (некалёный)', 'прозрачный (некалёный)', 'синий зеркальный (калёный)', 'йодовый зеркальный (калёный)', 'йодовый (калёный)', 'прозрачный (калёный)'],
            'Comfort' => ['синий зеркальный (некалёный)', 'йодовый зеркальный (некалёный)', 'йодовый (некалёный)', 'прозрачный (некалёный)']
        ][$category] ?? ['прозрачный (некалёный)'];
    }

    public static function getLockOptions($category)
    {
        return [
            'Lux' => ['Kale', 'Guardian'],
            'Premium' => ['Kale', 'Guardian', 'Galeon'],
            'Comfort' => ['Argus', 'Galeon']
        ][$category] ?? ['Argus'];
    }

    public static function calculateExtra($category, $innerTrim, $innerCover, $glassUnit)
    {
        $extra = [];
        
        if ($category === 'Premium' && stripos($innerTrim, 'шпон') !== false) {
            $extra[] = 'внутренняя обшивка - шпон (доплата)';
        }
        
        if ($category === 'Comfort' && stripos($innerCover, 'эмаль') !== false) {
            $extra[] = 'внутренняя обшивка - Эмаль (доплата)';
        }
        
        if ($category === 'Premium' && stripos($innerCover, 'шпон-марилька') !== false) {
            $extra[] = 'внутренняя обшивка - Шпон-марилька (доплата)';
        }
        
        if ($category === 'Premium' && stripos($glassUnit, 'калёный') !== false) {
            $extra[] = 'калёный стеклопакет (доплата)';
        }
        
        return implode('; ', $extra);
    }

    public static function validateFusionModel($model, $outerCover)
    {
        if (in_array($model, self::$fusionModels, true)) {
            $allowedCovers = ['МДФ-эмаль', 'Шпон-марилька'];
            if (!in_array($outerCover, $allowedCovers, true)) {
                return 'Для Fusion-модели выберите "МДФ-эмаль" или "Шпон-марилька"!';
            }
        }
        return null;
    }

    public static function generateContractNumber($branch)
    {
        $branch->increment('contract_counter');
        return $branch->code . '-' . str_pad($branch->contract_counter, 4, '0', STR_PAD_LEFT);
    }

    public static function validateContractNumberRange($contractNumber, $branchId)
    {
        $branchRanges = [
            'SHY-PP' => [20000, 29999], // Шымкент Прайм парк
            'SHY-RZ' => [30000, 39999], // Шымкент Ремзона
            'AKT' => [40000, 49999],    // Актобе
            'ALA-TST' => [50000, 57999], // Алматы Тастак
            'ALA-SC' => [58000, 59999],  // Алматы СтройСити
            'TRZ' => [100000, 119999],   // Тараз
            'ATR' => [120000, 139999],   // Атырау
            'TAS' => [60000, 69999],     // Ташкент
        ];

        $branch = \App\Models\Branch::find($branchId);
        if (!$branch || !isset($branchRanges[$branch->code])) {
            return 'Неизвестный филиал';
        }

        $range = $branchRanges[$branch->code];
        $number = (int) $contractNumber;

        if ($number < $range[0] || $number > $range[1]) {
            return "Номер договора должен быть в диапазоне {$range[0]}-{$range[1]} для филиала {$branch->name}";
        }

        return null;
    }
}
