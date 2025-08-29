<?php

namespace App\Records;

use App\Enum\GameRoles;

abstract class AbstractRowSheet
{
    public string $notes = '';
    public string $chosenQuestion = '';
    public ?GameRoles $chosenTrajectorie = null;
    public string $answer = '';
    public string $blamedFor = '';

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            $this->notes = $data->getValue('notes');
            $this->chosenQuestion = $data->getValue('chosenQuestion');
            $this->answer = $data->getValue('answer');
            $this->blamedFor = $data->getValue('blamedFor');
            $traj = $data->getValue('chosenTrajectorie');
            if ('' !== $traj) {
                $this->chosenTrajectorie = GameRoles::from($traj);
            }
        }
    }

    public function partialReady(): bool
    {
        return '' !== $this->blamedFor && '' !== $this->chosenQuestion && null !== $this->chosenTrajectorie;
    }
}
