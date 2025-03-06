<?php

namespace App\Twig\Tabs;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'tabs', template: 'components/tabs/tabs.twig')]
class TabsComponent
{
    public int $defaultSelected = 0;
}
