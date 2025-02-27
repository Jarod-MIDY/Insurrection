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

    public function getDescription(): string
    {
        return match ($this) {
            self::POWER => 'Jouer le Pouvoir, c’est être en haut de la pyramide et pourtant dans une situation constamment chancelante, ne reposant que sur des alliances stratégiques parfois précaires.',
            self::ORDER => 'Jouer l’Ordre, c’est être soumis au Pouvoir alors qu’on a les armes, essayer de protéger un Peuple qui vous méprise, essayer de faire tenir la charpente dans un monde qui brûle.',
            self::ECHO => 'Jouer l’Écho, c’est louvoyer entre déontologie, communication, investigation, proximité avec le Pouvoir et idéal de vérité. Et raconter tout ça devant vos yeux ébahis.',
            self::PEOPLE => 'Jouer le Peuple, c’est avoir la force du nombre et la faiblesse de l’inertie. Râler, quémander, commenter, manifester parfois. Personne ne fera rien sans vous, mais va d’abord falloir vous mobiliser.',
            self::MOLOTOV => 'Jouer le Molotov, c’est s’habiller en noir, aller dans la rue et se frotter aux forces de l’ordre.',
            self::BADGE => 'Jouer l’Écusson, c’est faire partie du système et s’en détacher progressivement.',
            self::PAMPHLET => 'Jouer le Pamphlet, c’est penser la révolution et imaginer l’après en essayant de ne pas faire l’économie des questions difficiles.',
            self::STAR => 'Jouer l’Étoile, c’est acquérir de l’influence peu à peu, jusqu’à se demander si on se bat pour ses idées ou pour sa propre hubris.',
        };
    }

    public function getIconName(): string
    {
        return match ($this) {
            self::POWER => 'crown',
            self::ORDER => 'police-badge',
            self::ECHO => 'newspaper',
            self::PEOPLE => 'uprising',
            self::MOLOTOV => 'molotov',
            self::BADGE => 'medal',
            self::PAMPHLET => 'full-folder',
            self::STAR => 'round-star',
        };
    }
}
