<?php

namespace App\Twig;

use App\Enum\GameRoles;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('RoleIcon')]
class RoleIconComponent
{
    public string $icon;

    public GameRoles $role;

    public bool $iconOnly = false;

    public const SVG_DIRECTORY = 'icons/roles';

    /**
     * Summary of __construct
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @throws \Exception
     */
    public function __construct(
        private Filesystem $filesystem,
    ) {
        if (!$this->filesystem->exists(self::SVG_DIRECTORY)) {
            throw new \Exception('SVG directory ' . self::SVG_DIRECTORY . ' not found in public');
        }
    }

    /**
     * Summary of mount
     * @param string $roleValue
     * @param bool $iconOnly
     * @throws \ValueError
     * @throws \TypeError
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @return void
     */
    public function mount(string $roleValue, bool $iconOnly = false): void
    {
        $this->iconOnly = $iconOnly;
        $this->role = GameRoles::from($roleValue);
        $this->icon = $this->filesystem->readFile(self::SVG_DIRECTORY . '/' . $this->role->getIconName() . '.svg');
    }
}
