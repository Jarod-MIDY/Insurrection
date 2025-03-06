<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class MolotovSheet implements CharacterSheet
{
    public const CHOICES_PART_OF = [
        'd\'un gang' => 'd\'un gang',
        'd\'un partie illégal' => 'd\'un partie illégal',
        'd\'une société secrète' => 'd\'une société secrète',
        'd\'une mouvance diffuse' => 'd\'une mouvance diffuse',
    ];

    public const CHOICES_DISSENT_REASON = [
        'suite à un abus' => 'suite à un abus',
        'pour venger un proche' => 'pour venger un proche',
        'pour te défouler' => 'pour te défouler',
        'malgré toi' => 'malgré toi',
    ];

    public string $name = '';

    public string $features = '';

    public string $partOf = '';

    public string $dissentReason = '';

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
            'partOf' => $this->partOf,
            'dissentReason' => $this->dissentReason,
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
        $this->partOf = $data['partOf'];
        $this->dissentReason = $data['dissentReason'];
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
                    'label' => 'Tu fais partie',
                    'value' => $this->partOf,
                ],
                [
                    'label' => 'Tu es entré en dissidence',
                    'value' => $this->dissentReason,
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
        return '' !== $this->name && '' !== $this->features && '' !== $this->partOf && '' !== $this->dissentReason && '' !== $this->ROWQuestion && '' !== $this->ROWAnswer && '' !== $this->trajQuestion && '' !== $this->trajAnswer
        && null !== $this->ROWRole && null !== $this->trajRole;
    }
}
