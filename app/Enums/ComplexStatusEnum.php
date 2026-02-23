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
            self::PLANNING     => 'Планируется',
            self::CONSTRUCTION => 'Строится',
            self::COMPLETED    => 'Завершён',
        };
    }
}
