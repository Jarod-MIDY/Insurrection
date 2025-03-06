<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class EchoSheet implements CharacterSheet
{
    public const CHOICES_FINANCED_BY = [
        'le Pouvoir' => 'le Pouvoir',
        'le Peuple' => 'le Peuple',
        'une puissance étrangère' => 'une puissance étrangère',
        'personne, et c’est bien le problème' => 'personne, et c’est bien le problème',
    ];

    public const CHOICES_IMPORTANT_AGENT_TYPE = [
        'des investigateurs' => 'des investigateurs',
        'des animateurs' => 'des animateurs',
        'des éditorialistes' => 'des éditorialistes',
        'des experts' => 'des experts',
    ];

    public const CHOICES_BLAMED_FOR = [
        'aux ordres' => 'aux ordres',
        'déconnectés' => 'déconnectés',
        'populistes' => 'populistes',
        'corporatistes' => 'corporatistes',
    ];

    public string $financedBy = '';
    public string $importantAgentType = '';
    public string $blamedFor = '';
    public string $notes = '';
    public string $chosenQuestion = '';
    public ?GameRoles $chosenTrajectorie = null;
    public string $answer = '';

    /**
     * @param array<string, string>|null $data
     */
    public function __construct(?array $data = null)
    {
        if ((bool) $data) {
            $this->__unserialize($data);
        }
    }

    public function __serialize(): array
    {
        return [
            'financedBy' => $this->financedBy,
            'importantAgentType' => $this->importantAgentType,
            'blamedFor' => $this->blamedFor,
            'notes' => $this->notes,
            'chosenQuestion' => $this->chosenQuestion,
            'answer' => $this->answer,
            'chosenTrajectorie' => $this->chosenTrajectorie?->value,
        ];
    }

    /**
     * @param array<string, string> $data
     */
    public function __unserialize(array $data): void
    {
        $this->financedBy = $data['financedBy'];
        $this->importantAgentType = $data['importantAgentType'];
        $this->blamedFor = $data['blamedFor'];
        $this->notes = $data['notes'];
        $this->chosenQuestion = $data['chosenQuestion'];
        $this->answer = $data['answer'];
        $this->chosenTrajectorie = GameRoles::from($data['chosenTrajectorie']);
    }

    public function getRenderData(): array
    {
        return [
            'listable' => [
                [
                    'label' => 'Vous êtes principalement financés par',
                    'value' => $this->financedBy,
                ],
                [
                    'label' => 'Vos représentants les plus influents sont',
                    'value' => $this->importantAgentType,
                ],
                [
                    'label' => 'On vous reproche principalement d\'abord d\'être',
                    'value' => $this->blamedFor,
                ],
            ],
            'question' => [
                'question' => $this->chosenQuestion,
                'answer' => $this->answer,
                'trajectorie' => $this->chosenTrajectorie?->value,
            ],
            'notes' => $this->notes,
        ];
    }

    public function isReady(): bool
    {
        return '' !== $this->financedBy && '' !== $this->importantAgentType && '' !== $this->blamedFor && '' !== $this->chosenQuestion && null !== $this->chosenTrajectorie;
    }
}
