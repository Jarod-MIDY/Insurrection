<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class StarSheet implements CharacterSheet
{
    public const CHOICES_QUALITY = [
        'ton acuité' => 'ton acuité',
        'ton optimisme' => 'ton optimisme',
        'ton imagination' => 'ton imagination',
        'à quel point on te sous-estime' => 'à quel point on te sous-estime',
    ];

    public const CHOICES_SHINE = [
        'en politique' => 'en politique',
        'dans les arts' => 'dans les arts',
        'chez une minorité' => 'chez une minorité',
        'dans une activité physique' => 'dans une activité physique',
    ];

    public string $name = '';

    public string $features = '';

    public string $bestQuality = '';

    public string $shineIn = '';

    public string $ROWQuestion = '';

    public ?GameRoles $ROWRole = null;

    public string $ROWAnswer = '';

    public string $trajQuestion = '';

    public ?GameRoles $trajRole = null;

    public string $trajAnswer = '';

    public string $notes = '';

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
            'name' => $this->name,
            'features' => $this->features,
            'bestQuality' => $this->bestQuality,
            'shineIn' => $this->shineIn,
            'ROWQuestion' => $this->ROWQuestion,
            'ROWAnswer' => $this->ROWAnswer,
            'trajQuestion' => $this->trajQuestion,
            'trajAnswer' => $this->trajAnswer,
            'notes' => $this->notes,
            'ROWRole' => $this->ROWRole?->value,
            'trajRole' => $this->trajRole?->value,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->features = $data['features'];
        $this->bestQuality = $data['bestQuality'];
        $this->shineIn = $data['shineIn'];
        $this->ROWQuestion = $data['ROWQuestion'];
        $this->ROWAnswer = $data['ROWAnswer'];
        $this->trajQuestion = $data['trajQuestion'];
        $this->trajAnswer = $data['trajAnswer'];
        $this->ROWRole = GameRoles::tryFrom($data['ROWRole']);
        $this->trajRole = GameRoles::tryFrom($data['trajRole']);
        $this->notes = $data['notes'];
    }

    public function getRenderData(): array
    {
        return [
            'listable' => [
                [
                    'label' => 'On t\'appelle',
                    'value' => $this->name,
                ],
                [
                    'label' => 'Ce qu\'on retient de toi',
                    'value' => $this->features,
                ],
                [
                    'label' => 'Ta plus grande qualité, c\'est',
                    'value' => $this->bestQuality,
                ],
                [
                    'label' => 'Tu brilles',
                    'value' => $this->shineIn,
                ],
            ],
            'row_question' => [
                'question' => $this->ROWQuestion,
                'answer' => $this->ROWAnswer,
                'role' => $this->ROWRole?->value,
            ],
            'traj_question' => [
                'question' => $this->trajQuestion,
                'answer' => $this->trajAnswer,
                'role' => $this->trajRole?->value,
            ],
            'notes' => $this->notes,
        ];
    }

    public function isReady(): bool
    {
        return '' !== $this->name && '' !== $this->features && '' !== $this->bestQuality && '' !== $this->shineIn && '' !== $this->ROWQuestion && '' !== $this->ROWAnswer && '' !== $this->trajQuestion && '' !== $this->trajAnswer
        && null !== $this->ROWRole && null !== $this->trajRole;
    }
}
