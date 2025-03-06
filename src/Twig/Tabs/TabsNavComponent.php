<?php

namespace App\Twig\Tabs;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'tabsNavElement', template: 'components/tabs/nav_element.twig')]
class TabsNavComponent
{
    public string $label;
}
