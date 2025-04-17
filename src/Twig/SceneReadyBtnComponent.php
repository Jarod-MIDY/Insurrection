<?php

namespace App\Twig;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('SceneReadyBtn')]
class SceneReadyBtnComponent
{
    use DefaultActionTrait;

    use ComponentToolsTrait;

    #[LiveProp(writable: false)]
    public Player $player;

    public function mount(Player $player): void
    {
        $this->player = $player;
    }

    #[LiveAction]
    public function changeReadyStatus(EntityManagerInterface $entityManager): void
    {
        $currentScene = $this->player->getGame()->getScenes()->last();
        $currentScene->setReadyPlayer($this->player);
        $entityManager->persist($currentScene);
        $entityManager->flush();
    }
}
