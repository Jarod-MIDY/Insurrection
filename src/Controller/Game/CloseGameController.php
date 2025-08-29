<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Enum\GameState;
use App\MercureEvent\Game\UpdateLoby;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/game/delete/{game}', name: 'app_game_delete')]
class CloseGameController extends AbstractController
{
    public function __invoke(
        Game $game,
        GameRepository $gameRepository,
        Request $request,
        UpdateLoby $updateLobySSE
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (
            $game->getAuthor() !== $this->getUser()
            || GameState::CLOSED === $game->getState()
        ) {
            throw $this->createAccessDeniedException();
        }
        if ($this->isCsrfTokenValid('delete' . $game->getId(), (string) $request->request->get('_token'))) {
            $game->setState(GameState::CLOSED);
            $gameRepository->save($game, true);
            $updateLobySSE();

            return $this->redirectToRoute('app_home');
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->redirectToRoute('app_game_edit', [
            'game' => $game->getId(),
        ]);
    }
}
