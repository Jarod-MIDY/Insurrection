<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class OrderSheet implements CharacterSheet
{
    public const CHOICES_FEARED_BECAUSE = [
        'l\'armée' => 'l\'armée',
        'la milice' => 'la milice',
        'l\'ordre mystique' => 'l\'ordre mystique',
        'la police secrète' => 'la police secrète',
    ];

    public const CHOICES_ACCOUNTABLE_TO = [
        'au Pouvoir' => 'au Pouvoir',
        'au Peuple' => 'au Peuple',
        'à personne' => 'à personne',
        'à un dogme' => 'à un dogme',
    ];

    public const CHOICES_BLAMED_FOR = [
        'violents' => 'violents',
        'intrusifs' => 'intrusifs',
        'corrompus' => 'corrompus',
        'inefficaces' => 'inefficaces',
    ];

    public string $fearedBecause = '';
    public string $accountableTo = '';
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

    public function __unserialize(array $data): void
    {
        $this->fearedBecause = $data['fearedBecause'];
        $this->accountableTo = $data['accountableTo'];
        $this->blamedFor = $data['blamedFor'];
        $this->notes = $data['notes'];
        $this->chosenQuestion = $data['chosenQuestion'];
        $this->answer = $data['answer'];
        $this->chosenTrajectorie = GameRoles::from($data['chosenTrajectorie']);
    }

    public function __serialize(): array
    {
        return [
            'fearedBecause' => $this->fearedBecause,
            'accountableTo' => $this->accountableTo,
            'blamedFor' => $this->blamedFor,
            'notes' => $this->notes,
            'chosenQuestion' => $this->chosenQuestion,
            'answer' => $this->answer,
            'chosenTrajectorie' => $this->chosenTrajectorie?->value,
        ];
    }

    public function getRenderData(): array
    {
        return [
            'listable' => [
                [
                    'label' => 'On vous craint',
                    'value' => $this->fearedBecause,
                ],
                [
                    'label' => 'On vous accuse',
                    'value' => $this->accountableTo,
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
        return '' !== $this->fearedBecause && '' !== $this->accountableTo && '' !== $this->blamedFor && '' !== $this->chosenQuestion && null !== $this->chosenTrajectorie;
    }
}
