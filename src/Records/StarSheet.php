<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

/**
 * @phpstan-type StarArray array{
 *      name: string,
 *      features: string,
 *      bestQuality: string,
 *      shineIn: string,
 *      ROWQuestion: string,
 *      ROWRole: string|null,
 *      ROWAnswer: string,
 *      trajQuestion: string,
 *      trajRole: string|null,
 *      trajAnswer: string,
 *      notes: string,
 * }
 */
class StarSheet implements CharacterSheet
{
    /**
     * @var array<string, string>
     */
    public const array CHOICES_QUALITY = [
        'ton acuité' => 'ton acuité',
        'ton optimisme' => 'ton optimisme',
        'ton imagination' => 'ton imagination',
        'à quel point on te sous-estime' => 'à quel point on te sous-estime',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_SHINE = [
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

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            $this->name = $data->getValue('name');
            $this->features = $data->getValue('features');
            $this->bestQuality = $data->getValue('bestQuality');
            $this->shineIn = $data->getValue('shineIn');
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
     * @return StarArray
     */
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

    /**
     * @param StarArray $data
     */
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
        $this->ROWRole = GameRoles::tryFrom($data['ROWRole'] ?? '');
        $this->trajRole = GameRoles::tryFrom($data['trajRole'] ?? '');
        $this->notes = $data['notes'];
    }

    public function getRenderData(): RoleRender
    {
        $renderData = new RoleRender($this->name, $this->features);
        $renderData->addListable('Ta plus grande qualidade, c\'est', $this->bestQuality);
        $renderData->addListable('Tu brilles', $this->shineIn);
        $renderData->setRowQuestion($this->ROWQuestion, $this->ROWAnswer, $this->ROWRole->value ?? '');
        $renderData->setTrajQuestion($this->trajQuestion, $this->trajAnswer, $this->trajRole->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    public function isReady(): bool
    {
        return '' !== $this->name && '' !== $this->features && '' !== $this->bestQuality && '' !== $this->shineIn && '' !== $this->ROWQuestion && '' !== $this->ROWAnswer && '' !== $this->trajQuestion && '' !== $this->trajAnswer
        && null !== $this->ROWRole && null !== $this->trajRole;
    }
}
