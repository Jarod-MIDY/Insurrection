<?php

namespace App\Service;

use App\Entity\Player;
use App\Enum\GameRoles;
use Doctrine\Common\Collections\Collection;

class RolesSelector
{
    public const MIN_TRAJECTORIES = 2;
    public const MIN_RIGHTS_OF_WAY = 3;

    /** @var GameRoles[] */
    private array $unAssignedroles = [];
    private int $missingTrajectories;
    private int $missingRightsOfWay;

    public function __construct(
    ) {
        $this->missingRightsOfWay = self::MIN_RIGHTS_OF_WAY;
        $this->missingTrajectories = self::MIN_TRAJECTORIES;
        $this->unAssignedroles = array_merge(GameRoles::getTrajectories(), GameRoles::getRightsOfWay());
    }

    /**
     * @param Collection<int,Player> $players
     *
     * @return Collection<int,Player>
     */
    public function attributeRolesToPlayer(Collection $players): array
    {
        $playerMissingRoles = $players->count();
        $sortedPlayers = $this->sortPlayersByChoiceWeight($players);
        foreach ($sortedPlayers as $player) {
            $freeChoice = $this->missingRightsOfWay <= 0 && $this->missingTrajectories <= 0
                        && $playerMissingRoles > ($this->missingTrajectories + $this->missingRightsOfWay);
            $role = $this->selectRole($player->getPreferedRoles(), $freeChoice);
            if (in_array($role, $this->unAssignedroles)) {
                $player->setRoles($role);
                --$playerMissingRoles;
                $this->unAssignedroles = array_udiff($this->unAssignedroles, [$role], fn (GameRoles $a, GameRoles $b): int => strcmp($a->value, $b->value));
            } else {
                throw new \LogicException(sprintf('Role %s is not available', $role->value));
            }
        }

        return $sortedPlayers;
    }

    private function sortPlayersByChoiceWeight(Collection $players): array
    {
        $playerArray = $players->toArray();
        usort(
            $playerArray,
            function (Player $a, Player $b): int {
                $nbPreferedA = [] === $a->getPreferedRoles() ? 8 : count($a->getPreferedRoles());
                $nbPreferedB = [] === $b->getPreferedRoles() ? 8 : count($b->getPreferedRoles());
                if ($nbPreferedA === $nbPreferedB) {
                    return 0;
                }
                if ($nbPreferedA > $nbPreferedB) {
                    return 1;
                }

                return -1;
            }
        );

        return $playerArray;
    }

    /**
     * @param GameRoles[] $preferedRoles
     */
    private function selectRole(array $preferedRoles, bool $freeChoice): GameRoles
    {
        $intersection = $this->rolesIntersect($preferedRoles, $this->unAssignedroles);
        if ($freeChoice) {
            if ([] === $intersection) {
                return $this->unAssignedroles[array_rand($this->unAssignedroles)];
            }

            return $intersection[array_rand($intersection)];
        } else {
            $nbPreferedTrajectories = count(GameRoles::filterTrajectories($preferedRoles));
            $nbPreferedRightsOfWay = count(GameRoles::filterRightsOfWay($preferedRoles));
            $roleType = $nbPreferedTrajectories > $nbPreferedRightsOfWay ? 'trajectories' : 'rightsOfWay';
            if (0 === $this->missingRightsOfWay && 'rightsOfWay' === $roleType) {
                $roleType = 'trajectories';
            }
            if (0 === $this->missingTrajectories && 'trajectories' === $roleType) {
                $roleType = 'rightsOfWay';
            }

            return $this->preferedOrRandom($intersection, $roleType);
        }
    }

    private function preferedOrRandom(array $intersection, string $roleType = 'trajectories'): GameRoles
    {
        $getRoleType = 'get'.ucfirst($roleType);
        $prefered = $this->rolesIntersect($intersection, GameRoles::{$getRoleType}());
        if ([] === $prefered) {
            $prefered = $this->rolesIntersect($this->unAssignedroles, GameRoles::{$getRoleType}());
        }
        --$this->{'missing'.ucfirst($roleType)};

        return $prefered[array_rand($prefered)];
    }

    /**
     * Summary of rolesIntersect.
     *
     * @param GameRoles[] $array1
     * @param GameRoles[] $array2
     *
     * @return GameRoles[]
     */
    private function rolesIntersect(array $array1, array $array2): array
    {
        return array_uintersect($array1, $array2,
            fn (GameRoles $a, GameRoles $b): int => strcmp($a->value, $b->value)
        );
    }
}
