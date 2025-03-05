<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class PeopleSheet implements CharacterSheet
{
    public const CHOICES_TRUST = [
        'le Pouvoir' => 'le Pouvoir',
        'l\'Ordre' => 'l\'Ordre',
        'l\'Écho' => 'l\'Écho',
        'vous-même uniquement' => 'vous-même uniquement',
    ];

    public const CHOICES_PRIORITY = [
        'la liberté' => 'la liberté',
        'la sécurité' => 'la sécurité',
        'l\'égalité' => 'l\'égalité',
        'la propriété' => 'la propriété',
    ];

    public const CHOICES_BLAMED_FOR = [
        'trop vous plaindre' => 'trop vous plaindre',
        'de penser qu\'à court terme' => 'de penser qu\'à court terme',
        'manquer d\'éducation' => 'manquer d\'éducation',
        'être idéalistes' => 'être idéalistes',
    ];

    public string $trust = '';
    public string $priority = '';
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
            'trust' => $this->trust,
            'priority' => $this->priority,
            'blamedFor' => $this->blamedFor,
            'notes' => $this->notes,
            'chosenQuestion' => $this->chosenQuestion,
            'answer' => $this->answer,
            'chosenTrajectorie' => $this->chosenTrajectorie?->value,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->trust = $data['trust'];
        $this->priority = $data['priority'];
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
                    'label' => 'Vous placeriez plutôt votre confiance dans',
                    'value' => $this->trust,
                ],
                [
                    'label' => 'Ce qui vous importe le plus, c\'est',
                    'value' => $this->priority,
                ],
                [
                    'label' => 'On vous reproche avant tout de',
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
}
