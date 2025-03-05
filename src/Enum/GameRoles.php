<?php

namespace App\Enum;

use App\Interface\CharacterSheet;
use App\Records\BadgeSheet;
use App\Records\EchoSheet;
use App\Records\MolotovSheet;
use App\Records\OrderSheet;
use App\Records\PamphletSheet;
use App\Records\PeopleSheet;
use App\Records\PowerSheet;
use App\Records\StarSheet;

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

    public function getImmersion(): string
    {
        return match ($this) {
            self::POWER => "C'est vous qui tirez les ficelles. Qu'elles soient économiques, législatives, religieuses ou féodales n'y change pas grand-chose. L'important, c'est que vous savez ce qui est bon pour les autres. Oui, même si les autres ne sont pas d'accord. Et non ce n'est pas immoral si vous en profitez au passage. Après tout, vous faites le job.",
            self::ORDER => "Si tout le monde se comportait de façon citoyenne, il n'y aurait pas de problème. Mais voilà, y'a toujours des petits malins pour se croire tout permis. Alors faut les rappeler à l'ordre ; de façon musclée si nécessaire. Et devant les autres des fois que ça leur donnerait des idées.",
            self::ECHO => "À l'époque moderne, on vous appelle le quatrième pouvoir. Mais la réalité, c'est que vous avez toujours été là. Ceux qui commentent, ceux qui informent. Les révolutions, c'est vous qui les déclenchez en nourrissant la colère du peuple comme on donne des bûches à un brasier. Et pourtant, c'est aussi vous qu'on retrouve à faire des courbettes aux puissants. Ça va, ça tire pas trop le grand écart ?",
            self::PEOPLE => "Des fins-fonds des campagnes aux centres des capitales, le Peuple est -au fond- ce autour de quoi tourne tous les autres. Mais le Peuple est aussi un pouvoir. Le pouvoir du nombre, déjà qui surpasse infiniment ceux qui voudraient l'arrêter, mais aussi le pouvoir de la volonté, car en fin de compte,le Peuple ne fait que ce qu'il veut bien faire. Encore faudrait-il que ce creuset de contradictions arrive à exprimer un désir clair.",
            self::PAMPHLET => "u es plutôt du type intellectuel. Avec des engagements politiques forts et des convictions chevillées au corps, tu prônes la défense des plus faibles, tu milites, tu revendiques. Quand il s'agit d'agir, tu préfères le symbole à l'affrontement. La violence, après tout, doit toujours rester le dernier des recours.",
            self::MOLOTOV => "Les grands discours, ça n'a jamais déboulonné aucune statue ou arrêté la moindre charge. Tu ne vis pas dans un roman, tu te confrontes au réel. Et tu y vas préparé·e, quitte à jouer les épouvantails et te faire des ennemis jusque dans ton propre camp. Pourtant, pas de doute, tu es efficace pour démonter. Oui, mais quand il faudra reconstruire ?",
            self::BADGE => "Tu travaillais pour l'Ordre, ou le Pouvoir, ou peut-être même pour l'Écho. En tout cas, tu étais ce qu'on pourrait appeler un maillon du système. Probablement pas le plus petit, d'ailleurs. Mais les choses ont changé. Tu sens bien que tu es devenu un corps étranger dans l'organisme, et que le système immunitaire ne va pas tarder à te rattraper si tu ne fais pas preuve d'une grande habileté...",
            self::STAR => "Tu n'es pas entrée dans l'arène il y a très longtemps, mais tu fais déjà des étincelles. Bien sûr, tu as des idées, des convictions. Bien entendu tu sais quelle cause tu défends. Mais tu la défendras mieux depuis le haut de la pyramide, non ? Et s'il faut faire quelques compromis pour y arriver ? Peut-être que le jeu en vaut la chandelle, après tout ?",
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

    public function getCharacterSheet(?array $data = null): CharacterSheet
    {
        return match ($this) {
            self::POWER => new PowerSheet($data),
            self::ORDER => new OrderSheet($data),
            self::ECHO => new EchoSheet($data),
            self::PEOPLE => new PeopleSheet($data),
            self::MOLOTOV => new MolotovSheet($data),
            self::BADGE => new BadgeSheet($data),
            self::PAMPHLET => new PamphletSheet($data),
            self::STAR => new StarSheet($data),
        };
    }

    public function isTrajectory(): bool
    {
        return in_array($this, self::getTrajectories());
    }

    public function isRightOfWay(): bool
    {
        return in_array($this, self::getRightsOfWay());
    }

    /**
     * @param GameRoles[] $roles
     *
     * @return GameRoles[]
     */
    public static function filterTrajectories(array $roles): array
    {
        return array_filter($roles, fn (GameRoles $role): bool => $role->isTrajectory());
    }

    /**
     * @param GameRoles[] $roles
     *
     * @return GameRoles[]
     */
    public static function filterRightsOfWay(array $roles): array
    {
        return array_filter($roles, fn (GameRoles $role): bool => $role->isRightOfWay());
    }
}
