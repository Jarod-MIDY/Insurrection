<?php

namespace App\Listener;

use App\Entity\Character;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Scene;
use App\Enum\GameState;
use App\Repository\CharacterRepository;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\SceneRepository;
use App\Service\RolesSelector;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Player::class)]
class PlayerUpdate
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private GameRepository $gameRepository,
        private CharacterRepository $characterRepository,
        private RolesSelector $rolesSelector,
        private SceneRepository $sceneRepository,
    ) {
    }

    public function postUpdate(Player $savedPlayer): void
    {
        $game = $savedPlayer->getGame();
        $this->rolesSelection($game);
        $this->startGame($game);
    }

    private function startGame(Game $game): void
    {
        if (GameState::LOBBY !== $game->getState()) {
            return;
        }
        $players = $game->getPlayers();
        $startGame = true;
        $characters = [];
        foreach ($players as $player) {
            if (!$player->isReadyToPlay()) {
                $startGame = false;
                break;
            }
            if ($player->getRole()->isTrajectory()) {
                $character = new Character();
                $character->setOwner($player);
                $character->setName($player->getInformations()['name']);
                $character->setFeatures($player->getInformations()['features']);
                $characters[] = $character;
            }
        }
        if ($startGame) {
            for ($i = 0; $i < 8; ++$i) {
                $arrayPlayers = $players->toArray();
                $playerIndex = array_rand($arrayPlayers);
                $arrayPlayers[$playerIndex]->addRadianceToken();
                $this->playerRepository->save($arrayPlayers[$playerIndex]);
            }
            $game->setState(GameState::PLAYING);
            $this->gameRepository->save($game, true);
            $startingScene = new Scene();
            $startingScene->setGame($game);
            $this->sceneRepository->save($startingScene, true);
            foreach ($characters as $character) {
                $this->characterRepository->save($character, false);
            }
            $this->characterRepository->getEntityManager()->flush();
        }
    }

    private function rolesSelection(Game $game): void
    {
        $players = $game->getPlayers();
        if ($players->count() < $game->getMaxPlayers()) {
            return;
        }
        $startRoleAttribution = true;
        foreach ($players as $player) {
            if ([] === $player->getPreferedRoles() || null !== $player->getRole()) {
                $startRoleAttribution = false;
                break;
            }
        }
        if ($startRoleAttribution) {
            $playersWithRoles = $this->rolesSelector->attributeRolesToPlayer($players);
            foreach ($playersWithRoles as $player) {
                $this->playerRepository->save($player);
            }
            $this->playerRepository->getEntityManager()->flush();
        }
    }
}
