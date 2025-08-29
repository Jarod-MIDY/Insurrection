<?php

namespace App\Controller\Scene;

use App\Entity\Player;
use App\Entity\Scene;
use App\Entity\SceneStory;
use App\Form\NewSceneFormType;
use App\Form\SceneStoryFormType;
use App\Repository\SceneStoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/player/{player}/scene/{scene}/story/edit', name: 'app_scene_edit_my_scene_storie')]
class EditMyStorieController extends AbstractController
{
    public function __invoke(
        Request $request,
        SceneStoryRepository $sceneStoryRepository,
        NewSceneFormType $form,
        Scene $scene,
        Player $player,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (
            null === $scene->getLeader()
            || !$scene->isStarted()
            || ($scene->getGame() !== $player->getGame())
            || ($this->getUser() !== $player->getLinkedUser())
        ) {
            throw $this->createAccessDeniedException();
        }

        $sceneStory = $sceneStoryRepository->findOneBy(['scene' => $scene, 'player' => $player]);
        if (null === $sceneStory) {
            $sceneStory = new SceneStory();
            $sceneStory->setScene($scene);
            $sceneStory->setPlayer($player);
        }

        $form = $this->createForm(SceneStoryFormType::class, $sceneStory, [
            'action' => $this->generateUrl('app_scene_edit_my_scene_storie', [
                'player' => $player->getId(),
                'scene' => $scene->getId(),
            ]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sceneStoryRepository->save($sceneStory, true);

            return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()?->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('scene_story/form.html.twig', [
            'form' => $form,
        ]);
    }
}
