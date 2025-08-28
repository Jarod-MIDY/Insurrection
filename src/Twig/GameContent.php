<?php

namespace App\Twig;

use App\Entity\Game;
use App\Entity\Player;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('GameContent', template: 'components/GameContent.html.twig')]
class GameContent
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?Game $game = null;

    #[LiveProp]
    public ?Player $player = null;

    public function mount(Game $game, Player $player): void
    {
        $this->game = $game;
        $this->player = $player;
    }

    #[LiveAction]
    public function refresh(): void
    {
    }
}
