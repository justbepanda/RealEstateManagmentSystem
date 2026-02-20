<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * Виды помещений
 */
enum PremiseTypeEnum: string
{
    case APARTMENT = 'apartment';
    case STUDIO = 'studio';
    case PENTHOUSE = 'penthouse';
    case COMMERCIAL = 'commercial';

    public function label(): string
    {
        return match($this) {
            self::APARTMENT => 'Квартира',
            self::STUDIO => 'Студия',
            self::PENTHOUSE => 'Пентхаус',
            self::COMMERCIAL => 'Коммерческое помещение',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
