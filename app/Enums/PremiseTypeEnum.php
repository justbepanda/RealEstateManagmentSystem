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
            self::APARTMENT  => 'Квартира',
            self::STUDIO     => 'Студия',
            self::PENTHOUSE  => 'Пентхаус',
            self::COMMERCIAL => 'Коммерческое',
        };
    }
}
