<?php

namespace App\Controller\Scene;

use App\Entity\Character;
use App\Entity\Scene;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/scene/{scene}/character/{character}/{isPresent}', name: 'app_scene_update_character_presence')]
class EditCharacterInSceneController extends AbstractController
{
    public function __invoke(
        Scene $scene, 
        Character $character, 
        SceneRepository $sceneRepository,
        bool $isPresent = true,
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $character->getOwner();
        if (null === $player) {
            throw $this->createAccessDeniedException();
        }
        if (
            null === $scene->getLeader()
            || ($scene->getGame() !== $player->getGame())
            || ($this->getUser() !== $player->getLinkedUser())
        ) {
            throw $this->createAccessDeniedException();
        }
        if ($isPresent) {
            $scene->addCharacter($character);
        } else {
            $scene->removeCharacter($character);
        }
        $sceneRepository->save($scene, true);

        return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()?->getId()]);
    }
}
