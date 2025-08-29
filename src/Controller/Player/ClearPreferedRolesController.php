<?php

namespace App\Controller\Player;

use App\Entity\Player;
use App\MercureEvent\Game\UpdateGame;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/player/{player}/clear-roles-preferences', name: 'app_player_clear_roles_preferences')]
class ClearPreferedRolesController extends AbstractController
{
    public function __invoke(
        Player $player,
        PlayerRepository $playerRepository,
        UpdateGame $updateGameSSE,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        if ([] === $player->getPreferedRoles() || null === $player->getPreferedRoles()) {
            return $this->redirectToRoute('app_player_save_roles_preferences', [
                'player' => $player->getId(),
            ]);
        }
        $player->setPreferedRoles([]);
        $playerRepository->save($player, true);

        $updateGameSSE((string) $player->getGame()?->getId());

        return $this->redirectToRoute('app_player_save_roles_preferences', [
            'player' => $player->getId(),
        ]);
    }
}
