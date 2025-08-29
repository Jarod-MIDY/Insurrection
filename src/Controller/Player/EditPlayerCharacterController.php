<?php

namespace App\Controller\Player;

use App\Entity\Character;
use App\Form\CharacterFormType;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/player/{player}/character/edit/{character}', name: 'app_character_edit')]
class EditPlayerCharacterController extends AbstractController
{
    public function __invoke(
        Character $character,
        Request $request,
        CharacterRepository $characterRepository,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $character->getOwner();
        if (null === $player) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(CharacterFormType::class, $character, [
            'action' => $this->generateUrl('app_character_edit', [
                'player' => $player->getId(),
                'character' => $character->getId(),
            ]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $characterRepository->save($character, true);

            return $this->redirectToRoute('app_game_show', [
                'game' => $player->getGame()?->getId(),
            ]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('character/form.html.twig', [
            'form' => $form,
        ]);
    }
}
