<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Player;
use App\Form\CharacterFormType;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

final class CharacterController extends AbstractController
{
    public function __construct(
        private CharacterRepository $characterRepository,
    ) {
    }

    #[Route('/player/{player}/character/add', name: 'app_character_add')]
    public function add(Player $player, Request $request): Response
    {
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
            $this->characterRepository->save($character, true);

            return $this->redirectToRoute('app_game_show', [
                'game' => $player->getGame()->getId(),
            ]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('character/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/player/{player}/character/edit/{character}', name: 'app_character_edit')]
    public function edit(Character $character, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $character->getOwner()->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(CharacterFormType::class, $character, [
            'action' => $this->generateUrl('app_character_edit', [
                'player' => $character->getOwner()->getId(),
                'character' => $character->getId(),
            ]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterRepository->save($character, true);

            return $this->redirectToRoute('app_game_show', [
                'game' => $character->getOwner()->getGame()->getId(),
            ]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('character/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/player/{player}/character/{character}/delete', name: 'app_character_delete')]
    public function delete(Character $character, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $character->getOwner()->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        $this->characterRepository->remove($character, true);

        return $this->redirectToRoute('app_player_show', [
            'player' => $character->getOwner()->getId(),
        ]);
    }
}
