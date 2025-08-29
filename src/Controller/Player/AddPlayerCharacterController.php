<?php

namespace App\Controller\Player;

use App\Entity\Character;
use App\Entity\Player;
use App\Form\CharacterFormType;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/player/{player}/character/add', name: 'app_character_add')]
class AddPlayerCharacterController extends AbstractController
{
    public function __invoke(
        Player $player,
        Request $request,
        CharacterRepository $characterRepository,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        if (null === $player->getRole()) {
            return $this->redirectToRoute('app_player_save_roles_preferences', [
                'player' => $player->getId(),
            ]);
        }

        $character = new Character();
        $character->setOwner($player);

        $form = $this->createForm(CharacterFormType::class, $character, [
            'action' => $this->generateUrl('app_character_add', [
                'player' => $player->getId(),
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
