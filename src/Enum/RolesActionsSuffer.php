<?php

namespace App\Enum;

enum RolesActionsSuffer: string
{
    case POWER_REVENDICATION_YIELD = 'une revendication à laquelle vous devez céder';
    case POWER_AGENTS_DEFECTIONS = 'de nombreuses défections chez vos agents';
    case POWER_SCANDAL = 'un scandale qui entache votre légitimité';
    case POWER_QUESTION = 'dévoiler la réponse à la question « Où avons-nous outrepassé nos droits ? »';

    case ORDER_BLUNDER = "les conséquences d'une bavure dans vos rangs";
    case ORDER_AGENTS_BURNOUT = 'un craquage nerveux de vos agents';
    case ORDER_DISCREDIT_FROM_AFAR = 'le désaveu de puissances étrangères';
    case ORDER_QUESTION = 'dévoiler la réponse à la question « Pourquoi arrêterions-nous de protéger le Pouvoir ? »';

    case ECHO_GLOBAL_HOSTILITY = "l'hostilité de tout le monde";
    case ECHO_LACK_OF_FUNDS = 'une absence de moyens au pire moment';
    case ECHO_AGENTS_DISAPEARANCE = 'la disparition brutale de certains de vos agents';
    case ECHO_QUESTION = 'dévoiler la réponse à la question « Quand avons-nous enfreint notre déontologie ? »';

    case PEOPLE_DRAMATIC_SHORTAGES = 'des pénuries dramatiques';
    case PEOPLE_INJUSTICE = 'des violences injustes';
    case PEOPLE_DEMOBILISATION = 'une démobilisation générale';
    case PEOPLE_QUESTION = 'dévoiler la réponse à la question « Pourquoi-est-ce que je me retournerais contre vous ? »';

    case PAMPHLET_BURNOUT = 'les effets du surmenage';
    case PAMPHLET_DISILLUSIONMENT = 'une cruelle désillusion';
    case PAMPHLET_INDIFFERENCE = "l'indifférence de presque toustes";
    case PAMPHLET_QUESTION = "dévoiler la réponse à la question « Quelle est la contradiction intérieure que j'assume le moins ? »";

    case MOLOTOV_AGGRESSION = 'un passage à tabac';
    case MOLOTOV_FALSE_ACUSATION = 'des accusations fallacieuses';
    case MOLOTOV_GENERAL_CONTEMPT = 'le mépris de presque toustes';
    case MOLOTOV_QUESTION = "dévoiler la réponse à la question « Qu'est-ce qui me ferait reculer ? »";

    case BADGE_LOST_RESOURCES = 'la perte de tes ressources';
    case BADGE_LOVED_ONES_ABANDONMENT = "l'abandon de tes proches";
    case BADGE_INSOMNIA = 'des insomnies';
    case BADGE_QUESTION = 'dévoiler la réponse à la question « Quelle règle importante ai-je enfreint ? »';

    case STAR_LOW_MOMENT = 'un passage à vide';
    case STAR_PEAR_PRESSURE = " l'intense pression de tes conseiller·es";
    case STAR_DEFAMATION = 'une campagne de diffamation';
    case STAR_QUESTION = 'dévoiler la réponse à la question « Pourquoi est-ce que je me sens illégitime ? »';

    public static function getActionsFromRole(GameRoles $role): array
    {
        return match ($role) {
            GameRoles::POWER => [
                self::POWER_REVENDICATION_YIELD,
                self::POWER_AGENTS_DEFECTIONS,
                self::POWER_SCANDAL,
                self::POWER_QUESTION,
            ],
            GameRoles::ORDER => [
                self::ORDER_BLUNDER,
                self::ORDER_AGENTS_BURNOUT,
                self::ORDER_DISCREDIT_FROM_AFAR,
                self::ORDER_QUESTION,
            ],
            GameRoles::ECHO => [
                self::ECHO_GLOBAL_HOSTILITY,
                self::ECHO_LACK_OF_FUNDS,
                self::ECHO_AGENTS_DISAPEARANCE,
                self::ECHO_QUESTION,
            ],
            GameRoles::PEOPLE => [
                self::PEOPLE_DRAMATIC_SHORTAGES,
                self::PEOPLE_INJUSTICE,
                self::PEOPLE_DEMOBILISATION,
                self::PEOPLE_QUESTION,
            ],
            GameRoles::PAMPHLET => [
                self::PAMPHLET_BURNOUT,
                self::PAMPHLET_DISILLUSIONMENT,
                self::PAMPHLET_INDIFFERENCE,
                self::PAMPHLET_QUESTION,
            ],
            GameRoles::MOLOTOV => [
                self::MOLOTOV_AGGRESSION,
                self::MOLOTOV_FALSE_ACUSATION,
                self::MOLOTOV_GENERAL_CONTEMPT,
                self::MOLOTOV_QUESTION,
            ],
            GameRoles::BADGE => [
                self::BADGE_LOST_RESOURCES,
                self::BADGE_LOVED_ONES_ABANDONMENT,
                self::BADGE_INSOMNIA,
                self::BADGE_QUESTION,
            ],
            GameRoles::STAR => [
                self::STAR_LOW_MOMENT,
                self::STAR_PEAR_PRESSURE,
                self::STAR_DEFAMATION,
                self::STAR_QUESTION,
            ],
        };
    }
}
