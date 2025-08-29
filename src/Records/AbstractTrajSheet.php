<?php

namespace App\Records;

use App\Enum\GameRoles;

abstract class AbstractTrajSheet
{
    public string $name = '';

    public string $features = '';

    public string $ROWQuestion = '';

    public ?GameRoles $ROWRole = null;

    public string $ROWAnswer = '';

    public string $trajQuestion = '';

    public ?GameRoles $trajRole = null;

    public string $trajAnswer = '';

    public string $notes = '';

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            $this->name = $data->getValue('name');
            $this->features = $data->getValue('features');
            $this->ROWQuestion = $data->getValue('ROWQuestion');
            $this->ROWAnswer = $data->getValue('ROWAnswer');
            $this->trajQuestion = $data->getValue('trajQuestion');
            $this->trajAnswer = $data->getValue('trajAnswer');
            $ROWRole = $data->getValue('ROWRole');
            $trajRole = $data->getValue('trajRole');
            if ('' !== $ROWRole) {
                $this->ROWRole = GameRoles::tryFrom($ROWRole);
            }
            if ('' !== $trajRole) {
                $this->trajRole = GameRoles::tryFrom($trajRole);
            }
            $this->notes = $data->getValue('notes');
        }
    }

    public function partialReady(): bool
    {
        return '' !== $this->name && '' !== $this->features && '' !== $this->ROWQuestion && '' !== $this->ROWAnswer && '' !== $this->trajQuestion && '' !== $this->trajAnswer
            && null !== $this->ROWRole && null !== $this->trajRole;
    }
}
