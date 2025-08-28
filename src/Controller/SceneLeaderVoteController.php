<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Scene;
use App\Entity\SceneLeaderVote;
use App\Form\VoteFormType;
use App\Repository\PlayerRepository;
use App\Repository\SceneLeaderVoteRepository;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class SceneLeaderVoteController extends AbstractController
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private SceneRepository $sceneRepository,
        private SceneLeaderVoteRepository $sceneLeaderVoteRepository,
    ) {
    }

    #[Route('/scene/{scene}/vote', name: 'app_scene_leader_vote')]
    public function vote(Request $request, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $game = $scene->getGame();
        if (null === $game) {
            throw $this->createAccessDeniedException();
        }
        $player = $this->playerRepository->findOneBy([
            'linkedUser' => $this->getUser(),
            'game' => $game,
        ]);
        if (null === $player) {
            throw $this->createAccessDeniedException();
        }
        $vote = $this->sceneLeaderVoteRepository->findOneBy([
            'player' => $player,
            'scene' => $scene,
        ]);
        if (null === $vote) {
            $vote = new SceneLeaderVote();
            $vote->setScene($scene);
            $vote->setPlayer($player);
        }
        $form = $this->createForm(VoteFormType::class, $vote, [
            'action' => $this->generateUrl('app_scene_leader_vote', [
                'scene' => $scene->getId(),
            ]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->redirectToRoute('app_game_show', ['game' => $scene->getGame()?->getId()]);
            $this->sceneLeaderVoteRepository->save($vote, true);
            if ($scene->getSceneLeaderVotes()->count() === $game->getMaxPlayers()) {
                $votes = $scene->getSceneLeaderVotes();
                $votesAsNumber = [];
                foreach ($votes as $vote) {
                    $key = $vote->getVotedForPlayer()?->getId();
                    if (null === $key) {
                        continue;
                    }
                    if (!isset($votesAsNumber[$key])) {
                        $votesAsNumber[$key] = 0;
                    }
                    ++$votesAsNumber[$key];
                }
                if ([] === $votesAsNumber) {
                    return $response;
                }
                $max = max($votesAsNumber);
                $maxVotes = array_filter($votesAsNumber, fn ($value) => $value === $max);
                if ([] === $maxVotes) {
                    return $response;
                }
                $randLeader = array_rand($maxVotes);
                $leader = $game->getPlayers()->filter(fn (Player $player) => $player->getId() === $randLeader)->first();
                if ($leader instanceof Player) {
                    $scene->setLeader($leader);
                    $this->sceneRepository->save($scene, true);
                }
            }

            return $response;
        }
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('scene_leader_vote/vote_form.html.twig', [
            'form' => $form,
        ]);
    }
}
