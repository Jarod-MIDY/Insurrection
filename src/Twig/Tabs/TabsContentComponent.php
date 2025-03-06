<?php

namespace App\Twig\Tabs;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'tabsContentElement', template: 'components/tabs/content_element.twig')]
class TabsContentComponent
{
    public string $label;

    public int $index;
}
