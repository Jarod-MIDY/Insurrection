<?php

namespace App\Controller;

use App\Entity\Game;
use App\Enum\GameState;
use App\Form\GameFormType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class GameController extends AbstractController
{
    public function __construct(
        private GameRepository $gameRepository,
    ) {
    }

    #[Route('/game/new', name: 'app_game_new')]
    public function newGame(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $game = new Game();
        $game->setAuthor($this->getUser());
        $game->setState(GameState::PLANNED);
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gameRepository->save($game, true);

            return $this->redirectToRoute('app_game_edit', ['game' => $game]);
        }

        return $this->render('game/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/game/edit/{game}', name: 'app_game_edit')]
    public function editGame(Request $request, Game $game): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gameRepository->save($game, true);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }
}
