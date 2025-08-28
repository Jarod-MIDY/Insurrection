<?php

namespace App\Enum;

enum RolesActionsObtain: string
{
    case POWER_ARREST = "l'arrestation de quelqu'un";
    case POWER_HELP_FROM_AFAR = "le soutien d'une puissance étrangère";
    case POWER_49_3 = 'les pleins pouvoirs, temporairement';
    case POWER_QUESTION = 'la réponse à la question « En quoi avez-vous besoin de nous »';

    case ORDER_ACQUITTAL = "l'acquittement de quelqu'un";
    case ORDER_MILITARY_INTERVENTION = 'une intervention militaire';
    case ORDER_MARTIAL_LAW = 'la loi martiale, temporairement';
    case ORDER_QUESTION = "la réponse à la question « Qu'est-ce qui vous ferait tenir tranquille ? »";

    case ECHO_DISREPUTE = "le discrédit de quelqu'un";
    case ECHO_ATTENTION_OF_ALL = "l'attention de tout le monde";
    case ECHO_MULTIPASS = "l'accès à une zone interdite";
    case ECHO_QUESTION = "la réponse à la question « Qu'est-ce que vous cachez ? »";

    case PEOPLE_TAKEOVER = "vous emparer d'un lieu symbolique";
    case PEOPLE_REVENDICATIONS = 'des revendications claires et précises';
    case PEOPLE_SHUTDOWN_ROW = 'la chute des institutions, temporairement';
    case PEOPLE_QUESTION = "la réponse à la question « Qu'est-ce qui vous pousserait à nous aider ? »";

    case PAMPHLET_MEETING = 'un rendez-vous avec les parties prenantes';
    case PAMPHLET_PUBLIC_MOB = 'une mobilisation publique importante';
    case PAMPHLET_LAST_WORD = 'le dernier mot';
    case PAMPHLET_QUESTION = "la réponse à la question « Pourquoi penses-tu que j'ai raison ? »";

    case MOLOTOV_HIDEOUT = "l'adresse d'une planque sûre";
    case MOLOTOV_BYSTANDER_PROTECTION = "la protection d'innocents";
    case MOLOTOV_TAKEOVER = "l'instauration d'une zone autonome";
    case MOLOTOV_QUESTION = 'la réponse à la question « Quel est ton point faible ? »';

    case BADGE_ESCAPE = 'une sortie de secours';
    case BADGE_PROTECT_SELF = 'un statut protégé';
    case BADGE_SECRET_FILES = 'des informations compromettantes';
    case BADGE_QUESTION = "la réponse à la question « Qu'est-ce qui te pousserait à m'aider ? »";

    case STAR_SYMPATHY = " la sympathie de n'importe qui";
    case STAR_HOST_SUPPORTERS = 'une foule de soutiens';
    case STAR_PRIVILEGES = 'une position privilégiée';
    case STAR_QUESTION = "la réponse à la question « Qu'est-ce qui te donnerait envie de me suivre ? »";

    /**
     * @return RolesActionsObtain[]
     */
    public static function getActionsFromRole(GameRoles $role): array
    {
        return match ($role) {
            GameRoles::POWER => [
                self::POWER_ARREST,
                self::POWER_HELP_FROM_AFAR,
                self::POWER_49_3,
                self::POWER_QUESTION,
            ],
            GameRoles::ORDER => [
                self::ORDER_ACQUITTAL,
                self::ORDER_MILITARY_INTERVENTION,
                self::ORDER_MARTIAL_LAW,
                self::ORDER_QUESTION,
            ],
            GameRoles::ECHO => [
                self::ECHO_DISREPUTE,
                self::ECHO_ATTENTION_OF_ALL,
                self::ECHO_MULTIPASS,
                self::ECHO_QUESTION,
            ],
            GameRoles::PEOPLE => [
                self::PEOPLE_TAKEOVER,
                self::PEOPLE_REVENDICATIONS,
                self::PEOPLE_SHUTDOWN_ROW,
                self::PEOPLE_QUESTION,
            ],
            GameRoles::PAMPHLET => [
                self::PAMPHLET_MEETING,
                self::PAMPHLET_PUBLIC_MOB,
                self::PAMPHLET_LAST_WORD,
                self::PAMPHLET_QUESTION,
            ],
            GameRoles::MOLOTOV => [
                self::MOLOTOV_HIDEOUT,
                self::MOLOTOV_BYSTANDER_PROTECTION,
                self::MOLOTOV_TAKEOVER,
                self::MOLOTOV_QUESTION,
            ],
            GameRoles::BADGE => [
                self::BADGE_ESCAPE,
                self::BADGE_PROTECT_SELF,
                self::BADGE_SECRET_FILES,
                self::BADGE_QUESTION,
            ],
            GameRoles::STAR => [
                self::STAR_SYMPATHY,
                self::STAR_HOST_SUPPORTERS,
                self::STAR_PRIVILEGES,
                self::STAR_QUESTION,
            ],
        };
    }

    public function getRoleFromAction(): GameRoles
    {
        return match ($this) {
            self::POWER_ARREST => GameRoles::POWER,
            self::POWER_HELP_FROM_AFAR => GameRoles::POWER,
            self::POWER_49_3 => GameRoles::POWER,
            self::POWER_QUESTION => GameRoles::POWER,
            self::ORDER_ACQUITTAL => GameRoles::ORDER,
            self::ORDER_MILITARY_INTERVENTION => GameRoles::ORDER,
            self::ORDER_MARTIAL_LAW => GameRoles::ORDER,
            self::ORDER_QUESTION => GameRoles::ORDER,
            self::ECHO_DISREPUTE => GameRoles::ECHO,
            self::ECHO_ATTENTION_OF_ALL => GameRoles::ECHO,
            self::ECHO_MULTIPASS => GameRoles::ECHO,
            self::ECHO_QUESTION => GameRoles::ECHO,
            self::PEOPLE_TAKEOVER => GameRoles::PEOPLE,
            self::PEOPLE_REVENDICATIONS => GameRoles::PEOPLE,
            self::PEOPLE_SHUTDOWN_ROW => GameRoles::PEOPLE,
            self::PEOPLE_QUESTION => GameRoles::PEOPLE,
            self::PAMPHLET_MEETING => GameRoles::PAMPHLET,
            self::PAMPHLET_PUBLIC_MOB => GameRoles::PAMPHLET,
            self::PAMPHLET_LAST_WORD => GameRoles::PAMPHLET,
            self::PAMPHLET_QUESTION => GameRoles::PAMPHLET,
            self::MOLOTOV_HIDEOUT => GameRoles::MOLOTOV,
            self::MOLOTOV_BYSTANDER_PROTECTION => GameRoles::MOLOTOV,
            self::MOLOTOV_TAKEOVER => GameRoles::MOLOTOV,
            self::MOLOTOV_QUESTION => GameRoles::MOLOTOV,
            self::BADGE_ESCAPE => GameRoles::BADGE,
            self::BADGE_PROTECT_SELF => GameRoles::BADGE,
            self::BADGE_SECRET_FILES => GameRoles::BADGE,
            self::BADGE_QUESTION => GameRoles::BADGE,
            self::STAR_SYMPATHY => GameRoles::STAR,
            self::STAR_HOST_SUPPORTERS => GameRoles::STAR,
            self::STAR_PRIVILEGES => GameRoles::STAR,
            self::STAR_QUESTION => GameRoles::STAR,
        };
    }
}
