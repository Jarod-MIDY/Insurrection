<?php

namespace App\Twig;

use App\Entity\Player;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Actions')]
class ActionsComponent
{
    public Player $player;
}
