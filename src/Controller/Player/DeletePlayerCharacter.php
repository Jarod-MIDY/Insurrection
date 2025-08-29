<?php

namespace App\Controller\Player;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/player/{player}/character/{character}/delete', name: 'app_character_delete')]
class DeletePlayerCharacter extends AbstractController
{
    public function __invoke(
        Character $character, 
        CharacterRepository $characterRepository    
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $character->getOwner();
        if (null === $player) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        $characterRepository->remove($character, true);

        return $this->redirectToRoute('app_player_role_edit', [
            'player' => $player->getId(),
        ]);
    }
}
