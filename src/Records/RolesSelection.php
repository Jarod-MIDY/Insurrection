<?php

namespace App\Records;

use App\Enum\GameRoles;

class RolesSelection
{
    /**
     * @var GameRoles[]
     */
    public array $trajectories = [];

    /**
     * @var GameRoles[]
     */
    public array $rightsOfWay = [];

    /**
     * @return GameRoles[]
     */
    public function getRoles(): array
    {
        return array_merge($this->trajectories, $this->rightsOfWay);
    }
}
