<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

/**
 * @phpstan-type PamphletArray array{
 *      name: string,
 *      features: string,
 *      origins: string,
 *      modusOperendi: string,
 *      ROWQuestion: string,
 *      ROWRole: string|null,
 *      ROWAnswer: string,
 *      trajQuestion: string,
 *      trajRole: string|null,
 *      trajAnswer: string,
 *      notes: string,
 * }
 */
class PamphletSheet extends AbstractTrajSheet implements CharacterSheet
{
    /**
     * @var array<string, string>
     */
    public const array CHOICES_ORIGINS = [
        'modeste' => 'modeste',
        'lettrée' => 'lettrée',
        'aristocratique' => 'aristocratique',
        'étrangère' => 'étrangère',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_MODUS_OPERENDI = [
        'les happenings' => 'les happenings',
        'la désobéissance civile' => 'la désobéissance civile',
        'la publication de textes' => 'la publication de textes',
        'les démarches légales' => 'les démarches legislatives',
    ];

    public string $origins = '';

    public string $modusOperendi = '';

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            parent::__construct($data);
            $this->origins = $data->getValue('firstRedTapeRisk');
            $this->modusOperendi = $data->getValue('dissentGoal');
        }
    }

    /**
     * @return PamphletArray
     */
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

    /**
     * @param PamphletArray $data
     */
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
        $this->ROWRole = (bool) $data['ROWRole'] ? GameRoles::tryFrom($data['ROWRole']) : null;
        $this->trajRole = (bool) $data['trajRole'] ? GameRoles::tryFrom($data['trajRole']) : null;
    }

    public function getRenderData(): RoleRender
    {
        $renderData = new RoleRender($this->name, $this->features);
        $renderData->addListable('Tu es d\'origine', $this->origins);
        $renderData->addListable('Ton moyen d\'action favori, c\'est', $this->modusOperendi);
        $renderData->setRowQuestion($this->ROWQuestion, $this->ROWAnswer, $this->ROWRole->value ?? '');
        $renderData->setTrajQuestion($this->trajQuestion, $this->trajAnswer, $this->trajRole->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    public function isReady(): bool
    {
        return '' !== $this->origins && '' !== $this->modusOperendi && $this->partialReady();
    }
}
