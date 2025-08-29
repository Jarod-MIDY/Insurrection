<?php

namespace App\Controller\Scene;

use App\Entity\Scene;
use App\Form\NewSceneFormType;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/scene/{scene}', name: 'app_scene_edit')]
class EditSceneController extends AbstractController
{
    public function __invoke(
        Request $request, 
        Scene $scene,
        SceneRepository $sceneRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($scene->getLeader()?->getLinkedUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(NewSceneFormType::class, $scene, [
            'action' => $this->generateUrl('app_scene', ['scene' => $scene->getId()]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sceneRepository->save($scene, true);

            return $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()?->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('scene/form.html.twig', [
            'form' => $form,
        ]);
    }
}
