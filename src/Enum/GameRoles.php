<?php

namespace App\Enum;

enum GameRoles: string
{
    case POWER = 'power';

    case ORDER = 'order';

    case ECHO = 'echo';

    case PEOPLE = 'people';

    case MOLOTOV = 'molotov';

    case BADGE = 'badge';

    case PAMPHLET = 'pamphlet';

    case STAR = 'star';

    /**
     * @return GameRoles[]
     */
    public static function getTrajectories(): array
    {
        return [
            self::MOLOTOV,
            self::BADGE,
            self::PAMPHLET,
            self::STAR,
        ];
    }

    /**
     * @return GameRoles[]
     */
    public static function getRightsOfWay(): array
    {
        return [
            self::POWER,
            self::ORDER,
            self::ECHO,
            self::PEOPLE,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::POWER => 'Pouvoir',
            self::ORDER => 'Ordre',
            self::ECHO => 'Echo',
            self::PEOPLE => 'Peuple',
            self::MOLOTOV => 'Molotov',
            self::BADGE => 'Ecusson',
            self::PAMPHLET => 'Pamphlet',
            self::STAR => 'Etoile',
        };
    }
}
