<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

/**
 * @phpstan-type MolotovArray array{
 *      name: string,
 *      features: string,
 *      partOf: string,
 *      dissentReason: string,
 *      ROWQuestion: string,
 *      ROWRole: string|null,
 *      ROWAnswer: string,
 *      trajQuestion: string,
 *      trajRole: string|null,
 *      trajAnswer: string,
 *      notes: string,
 * }
 */
class MolotovSheet extends AbstractTrajSheet implements CharacterSheet
{
    /**
     * @var array<string, string>
     */
    public const array CHOICES_PART_OF = [
        'd\'un gang' => 'd\'un gang',
        'd\'un partie illégal' => 'd\'un partie illégal',
        'd\'une société secrète' => 'd\'une société secrète',
        'd\'une mouvance diffuse' => 'd\'une mouvance diffuse',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_DISSENT_REASON = [
        'suite à un abus' => 'suite à un abus',
        'pour venger un proche' => 'pour venger un proche',
        'pour te défouler' => 'pour te défouler',
        'malgré toi' => 'malgré toi',
    ];

    public string $partOf = '';

    public string $dissentReason = '';

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            parent::__construct($data);
            $this->partOf = $data->getValue('partOf');
            $this->dissentReason = $data->getValue('dissentReason');
        }
    }

    /**
     * @return MolotovArray
     */
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
     * @param MolotovArray $data
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
        $this->ROWRole = (bool) $data['ROWRole'] ? GameRoles::tryFrom($data['ROWRole']) : null;
        $this->trajRole = (bool) $data['trajRole'] ? GameRoles::tryFrom($data['trajRole']) : null;
        $this->notes = $data['notes'];
    }

    public function getRenderData(): RoleRender
    {
        $renderData = new RoleRender($this->name, $this->features);
        $renderData->addListable('Tu fais partie', $this->partOf);
        $renderData->addListable('Tu es entré en dissidence', $this->dissentReason);
        $renderData->setRowQuestion($this->ROWQuestion, $this->ROWAnswer, $this->ROWRole->value ?? '');
        $renderData->setTrajQuestion($this->trajQuestion, $this->trajAnswer, $this->trajRole->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    public function isReady(): bool
    {
        return '' !== $this->partOf && '' !== $this->dissentReason && $this->partialReady();
    }
}
