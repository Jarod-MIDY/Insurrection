<?php

namespace App\Twig;

use App\Enum\GameRoles;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('RoleIcon')]
class RoleIconComponent
{
    public string $icon;

    public const SVG_DIRECTORY = 'icons/roles';

    public function __construct(
        private Filesystem $filesystem,
    ) {
        if (!$this->filesystem->exists(self::SVG_DIRECTORY)) {
            throw new \Exception('SVG directory '.self::SVG_DIRECTORY.' not found in public');
        }
    }

    public function mount(GameRoles $role): void
    {
        $this->icon = $this->filesystem->readFile(self::SVG_DIRECTORY.'/'.$role->getIconName().'.svg');
    }
}
