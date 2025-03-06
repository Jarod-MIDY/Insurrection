<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class BadgeSheet implements CharacterSheet
{
    public const CHOICES_RED_TAPE = [
        'en fuitant des informations' => 'en fuitant des informations',
        'en ignorant un ordre direct' => 'en ignorant un ordre direct',
        'en désertant' => 'en désertant',
        'à ton insu' => 'à ton insu',
    ];

    public const CHOICES_DISSENT_GOAL = [
        'faire un maximum de dégâts' => 'faire un maximum de dégâts',
        'disparaître' => 'disparaître',
        'dévoiler la vérité' => 'dévoiler la vérité',
        'retrouver ta place' => 'retrouver ta place',
    ];

    public string $name = '';

    public string $features = '';

    public string $firstRedTapeRisk = '';

    public string $dissentGoal = '';

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
            'firstRedTapeRisk' => $this->firstRedTapeRisk,
            'dissentGoal' => $this->dissentGoal,
            'ROWQuestion' => $this->ROWQuestion,
            'ROWAnswer' => $this->ROWAnswer,
            'trajQuestion' => $this->trajQuestion,
            'trajAnswer' => $this->trajAnswer,
            'notes' => $this->notes,
            'ROWRole' => $this->ROWRole?->value,
            'trajRole' => $this->trajRole?->value,
        ];
    }

    /**
     * @param array<string, string> $data
     */
    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->features = $data['features'];
        $this->firstRedTapeRisk = $data['firstRedTapeRisk'];
        $this->dissentGoal = $data['dissentGoal'];
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
                    'label' => 'Tu as déjà franchi la ligne rouge',
                    'value' => $this->firstRedTapeRisk,
                ],
                [
                    'label' => 'Tout ce que tu souhaites, c\'est',
                    'value' => $this->dissentGoal,
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
        return '' !== $this->name && '' !== $this->features && '' !== $this->firstRedTapeRisk && '' !== $this->dissentGoal && '' !== $this->ROWQuestion && '' !== $this->ROWAnswer && '' !== $this->trajQuestion && '' !== $this->trajAnswer
        && null !== $this->ROWRole && null !== $this->trajRole;
    }
}
