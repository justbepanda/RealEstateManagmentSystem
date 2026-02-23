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

    /**
     * @return array
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = ucfirst($case->value);
        }

        return $options;
    }

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::AVAILABLE    => 'Доступно',
            self::RESERVED     => 'Забронировано',
            self::SOLD         => 'Продано',
            self::NOT_FOR_SALE => 'Не для продажи',
        };
    }
}
