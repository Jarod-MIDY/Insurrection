<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Form\GameFormType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/game/edit/{game}', name: 'app_game_edit')]
class EditGameController extends AbstractController
{
    public function __invoke(
        Game $game, 
        Request $request, 
        GameRepository $gameRepository,
        HubInterface $hub
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->save($game, true);
            $hub->publish(new Update(
                'UpdateLoby',
                '{}',
            ));
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }
}
