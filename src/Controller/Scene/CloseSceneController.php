<?php

namespace App\Controller\Scene;

use App\Entity\Scene;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/scene/{scene}/close', name: 'app_scene_close')]
class CloseSceneController extends AbstractController
{
    public function __invoke(
        Request $request,
        Scene $scene,
        SceneRepository $sceneRepository,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (
            $scene->getLeader()?->getLinkedUser() !== $this->getUser()
            || !$scene->isStarted()
        ) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$scene->getId(), (string) $request->request->get('_token'))) {
            $scene->setFinishedAt(new \DateTimeImmutable());
            $sceneRepository->save($scene);
            $newScene = new Scene();
            $newScene->setGame($scene->getGame());
            $sceneRepository->save($newScene, true);

            return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()?->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('scene/close.html.twig', [
            'scene' => $scene,
        ]);
    }
}
