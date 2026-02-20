<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * Статусы помещений
 */
enum PremiseStatusEnum: string
{
    case AVAILABLE = 'available';
    case RESERVED = 'reserved';
    case SOLD = 'sold';
    case NOT_FOR_SALE = 'not_for_sale';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Свободно',
            self::RESERVED => 'Забронировано',
            self::SOLD => 'Продано',
            self::NOT_FOR_SALE => 'Не для продажи',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
