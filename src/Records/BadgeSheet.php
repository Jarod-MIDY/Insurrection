<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class BadgeSheet extends AbstractTrajSheet implements CharacterSheet
{
    /**
     * @var array<string, string>
     */
    public const array CHOICES_RED_TAPE = [
        'en fuitant des informations' => 'en fuitant des informations',
        'en ignorant un ordre direct' => 'en ignorant un ordre direct',
        'en désertant' => 'en désertant',
        'à ton insu' => 'à ton insu',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_DISSENT_GOAL = [
        'faire un maximum de dégâts' => 'faire un maximum de dégâts',
        'disparaître' => 'disparaître',
        'dévoiler la vérité' => 'dévoiler la vérité',
        'retrouver ta place' => 'retrouver ta place',
    ];

    public string $firstRedTapeRisk = '';

    public string $dissentGoal = '';

    /**
     * Summary of __construct
     * @throws \TypeError
     * @throws \ValueError
     * @param null|\App\Records\InformationCollection $data
     */
    public function __construct(null|InformationCollection $data = null)
    {
        if (null !== $data) {
            parent::__construct($data);
            $this->firstRedTapeRisk = $data->getValue('firstRedTapeRisk');
            $this->dissentGoal = $data->getValue('dissentGoal');
        }
    }

    /**
     * @return array{
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
    #[\Override]
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
     * @param array{
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
     * } $data
     * @throws \TypeError
     * @throws \ValueError
     */
    #[\Override]
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
        $this->ROWRole = (bool) $data['ROWRole'] ? GameRoles::tryFrom($data['ROWRole'] ?? 'null') : null;
        $this->trajRole = (bool) $data['trajRole'] ? GameRoles::tryFrom($data['trajRole'] ?? 'null') : null;
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

    #[\Override]
    public function isReady(): bool
    {
        return '' !== $this->firstRedTapeRisk && '' !== $this->dissentGoal && $this->partialReady();
    }
}
