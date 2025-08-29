<?php

namespace App\Twig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('frameRefresh', )]
class FrameRefreshComponent
{
    public bool $withText = false;
}
