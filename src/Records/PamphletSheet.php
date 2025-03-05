<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class PamphletSheet implements CharacterSheet
{
    public const CHOICES_ORIGINS = [
        'modeste' => 'modeste',
        'lettrée' => 'lettrée',
        'aristocratique' => 'aristocratique',
        'étrangère' => 'étrangère',
    ];

    public const CHOICES_MODUS_OPERENDI = [
        'les happenings' => 'les happenings',
        'la désobéissance civile' => 'la désobéissance civile',
        'la publication de textes' => 'la publication de textes',
        'les démarches légales' => 'les démarches legislatives',
    ];

    public string $name = '';

    public string $features = '';

    public string $origins = '';

    public string $modusOperendi = '';

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
            'origins' => $this->origins,
            'modusOperendi' => $this->modusOperendi,
            'notes' => $this->notes,
            'ROWQuestion' => $this->ROWQuestion,
            'ROWAnswer' => $this->ROWAnswer,
            'trajQuestion' => $this->trajQuestion,
            'trajAnswer' => $this->trajAnswer,
            'ROWRole' => $this->ROWRole?->value,
            'trajRole' => $this->trajRole?->value,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->features = $data['features'];
        $this->origins = $data['origins'];
        $this->modusOperendi = $data['modusOperendi'];
        $this->notes = $data['notes'];
        $this->ROWQuestion = $data['ROWQuestion'];
        $this->ROWAnswer = $data['ROWAnswer'];
        $this->trajQuestion = $data['trajQuestion'];
        $this->trajAnswer = $data['trajAnswer'];
        $this->ROWRole = GameRoles::tryFrom($data['ROWRole']);
        $this->trajRole = GameRoles::tryFrom($data['trajRole']);
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
                    'label' => 'Tu es d\'origine',
                    'value' => $this->origins,
                ],
                [
                    'label' => 'Ton moyen d\'action favori, c\'est',
                    'value' => $this->modusOperendi,
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
}
