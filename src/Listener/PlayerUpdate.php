<?php

namespace App\Listener;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Service\RolesSelector;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Player::class)]
class PlayerUpdate
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private RolesSelector $rolesSelector,
    ) {
    }

    public function postUpdate(Player $savedPlayer): void
    {
        $game = $savedPlayer->getGame();
        $players = $game->getPlayers();
        if ($players->count() < $game->getMaxPlayers()) {
            return;
        }
        $startRoleAttribution = true;
        foreach ($players as $player) {
            if ([] === $player->getPreferedRoles() || null !== $player->getRoles()) {
                $startRoleAttribution = false;
                break;
            }
        }
        if ($startRoleAttribution) {
            $playersWithRoles = $this->rolesSelector->attributeRolesToPlayer($game->getPlayers());
            foreach ($playersWithRoles as $player) {
                $this->playerRepository->save($player);
            }
            $this->playerRepository->getEntityManager()->flush();
        }
    }
}
