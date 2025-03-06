<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class PowerSheet implements CharacterSheet
{
    public const CHOICE_LEGITIMACY = [
        'économique' => 'économique',
        'religieuse' => 'religieuse',
        'républicaine' => 'républicaine',
        'dynastique' => 'dynastique',
    ];

    public const CHOICE_AGENT_TYPE = [
        'des élus' => 'des élus',
        'une caste' => 'une caste',
        'des fonctionnaires' => 'des fonctionnaires',
        'une famille' => 'une famille',
    ];

    public const CHOICE_BLAMED = [
        'la peur' => 'la peur',
        'l\'omniprésence' => 'l\'omniprésence',
        'l\'esbroufe' => 'l\'esbroufe',
        'le clientélisme' => 'le clientélisme',
    ];

    public string $legitimacy = '';
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
            'legitimacy' => $this->legitimacy,
            'importantAgentType' => $this->importantAgentType,
            'blamedFor' => $this->blamedFor,
            'notes' => $this->notes,
            'chosenQuestion' => $this->chosenQuestion,
            'answer' => $this->answer,
            'chosenTrajectorie' => $this->chosenTrajectorie?->value,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->legitimacy = $data['legitimacy'];
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
                    'label' => 'Votre légitimité est',
                    'value' => $this->legitimacy,
                ],
                [
                    'label' => 'Vos agents importants sont',
                    'value' => $this->importantAgentType,
                ],
                [
                    'label' => 'On vous reproche surtout de gouverner par',
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
        return '' !== $this->legitimacy && '' !== $this->importantAgentType && '' !== $this->blamedFor && '' !== $this->chosenQuestion && null !== $this->chosenTrajectorie;
    }
}
