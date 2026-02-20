<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * Статусы ЖК
 */
enum ComplexStatusEnum: string
{
    case PLANNING = 'planning';
    case CONSTRUCTION = 'construction';
    case COMPLETED = 'completed';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PLANNING => 'Планирование',
            self::CONSTRUCTION => 'Строительство',
            self::COMPLETED => 'Завершено',
        };
    }

    /**
     * @return array
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
