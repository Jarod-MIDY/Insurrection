<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Form\GameFormType;
use App\MercureEvent\Game\UpdateLoby;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/game/edit/{game}', name: 'app_game_edit')]
class EditGameController extends AbstractController
{
    public function __invoke(
        Game $game,
        Request $request,
        GameRepository $gameRepository,
        UpdateLoby $updateLobySSE,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->save($game, true);
            $updateLobySSE();
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }
}
