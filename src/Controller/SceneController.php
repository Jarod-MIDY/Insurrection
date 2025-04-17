<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Player;
use App\Entity\Scene;
use App\Entity\SceneStory;
use App\Form\NewSceneFormType;
use App\Form\SceneStoryFormType;
use App\Repository\SceneRepository;
use App\Repository\SceneStoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class SceneController extends AbstractController
{
    public function __construct(
        private SceneRepository $sceneRepository,
    ) {
    }

    #[Route('/scene/{scene}', name: 'app_scene')]
    public function edit(Request $request, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($scene->getLeader()->getLinkedUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(NewSceneFormType::class, $scene, [
            'action' => $this->generateUrl('app_scene', ['scene' => $scene->getId()]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->sceneRepository->save($scene, true);

            return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('scene/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/scene/{scene}/close', name: 'app_scene_close')]
    public function closeScene(Request $request, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (
            $scene->getLeader()->getLinkedUser() !== $this->getUser()
            || !$scene->isStarted()
        ) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$scene->getId(), (string) $request->request->get('_token'))) {
            $scene->setFinishedAt(new \DateTimeImmutable());
            $this->sceneRepository->save($scene);
            $newScene = new Scene();
            $newScene->setGame($scene->getGame());
            $this->sceneRepository->save($newScene, true);

            return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('scene/close.html.twig', [
            'scene' => $scene,
        ]);
    }

    #[Route('/scene/{scene}/character/{character}/{isPresent}', name: 'app_scene_update_character_presence')]
    public function updateCharacterPresence(Scene $scene, Character $character, bool $isPresent = true): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (
            null === $scene->getLeader()
            || ($scene->getGame() !== $character->getOwner()->getGame())
            || ($this->getUser() !== $character->getOwner()->getLinkedUser())
        ) {
            throw $this->createAccessDeniedException();
        }
        if ($isPresent) {
            $scene->addCharacter($character);
        } else {
            $scene->removeCharacter($character);
        }
        $this->sceneRepository->save($scene, true);

        return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()->getId()]);
    }

    #[Route('/player/{player}/scene/{scene}/story/edit', name: 'app_scene_edit_my_scene_storie')]
    public function editMySceneStorie(
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
        if (null !== $sceneStory) {
            $sceneStory = new SceneStory();
            $sceneStory->setScene($scene);
            $sceneStory->setPlayer($player);
        }

        $form = $this->createForm(SceneStoryFormType::class, $sceneStory, [
            'action' => $this->generateUrl('app_scene_edit_my_scene_storie', [
                'player' => $player->getId(), 'scene' => $scene->getId(),
            ]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sceneStoryRepository->save($sceneStory, true);

            return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('scene_story/form.html.twig', [
            'form' => $form,
        ]);
    }
}
