<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

/**
 * @phpstan-type BadgeArray array{
 *      name: string,
 *      features: string,
 *      firstRedTapeRisk: string,
 *      dissentGoal: string,
 *      ROWQuestion: string,
 *      ROWRole: string|null,
 *      ROWAnswer: string,
 *      trajQuestion: string,
 *      trajRole: string|null,
 *      trajAnswer: string,
 *      notes: string,
 * }
 */
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

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            $this->name = $data->getValue('name');
            $this->features = $data->getValue('features');
            $this->firstRedTapeRisk = $data->getValue('firstRedTapeRisk');
            $this->dissentGoal = $data->getValue('dissentGoal');
            $this->ROWQuestion = $data->getValue('ROWQuestion');
            $this->ROWAnswer = $data->getValue('ROWAnswer');
            $this->trajQuestion = $data->getValue('trajQuestion');
            $this->trajAnswer = $data->getValue('trajAnswer');
            $this->ROWRole = GameRoles::tryFrom($data->getValue('ROWRole'));
            $this->trajRole = GameRoles::tryFrom($data->getValue('trajRole'));
            $this->notes = $data->getValue('notes');
        }
    }

    /**
     * @return BadgeArray
     */
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
     * @param BadgeArray $data
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
        $this->ROWRole = GameRoles::tryFrom($data['ROWRole'] ?? '');
        $this->trajRole = GameRoles::tryFrom($data['trajRole'] ?? '');
        $this->notes = $data['notes'];
    }

    public function getRenderData(): RoleRender
    {
        $renderData = new RoleRender($this->name, $this->features);
        $renderData->addListable('Tu as déjà franchi la ligne rouge', $this->firstRedTapeRisk);
        $renderData->addListable('Tout ce que tu souhaites, c\'est', $this->dissentGoal);
        $renderData->setRowQuestion($this->ROWQuestion, $this->ROWAnswer, $this->ROWRole->value ?? '');
        $renderData->setTrajQuestion($this->trajQuestion, $this->trajAnswer, $this->trajRole->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    public function isReady(): bool
    {
        return '' !== $this->name && '' !== $this->features && '' !== $this->firstRedTapeRisk && '' !== $this->dissentGoal && '' !== $this->ROWQuestion && '' !== $this->ROWAnswer && '' !== $this->trajQuestion && '' !== $this->trajAnswer
        && null !== $this->ROWRole && null !== $this->trajRole;
    }
}
