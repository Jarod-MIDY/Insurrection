<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class PeopleSheet extends AbstractRowSheet implements CharacterSheet
{
    /**
     * @var array<string, string>
     */
    public const array CHOICES_TRUST = [
        'le Pouvoir' => 'le Pouvoir',
        'l\'Ordre' => 'l\'Ordre',
        'l\'Écho' => 'l\'Écho',
        'vous-même uniquement' => 'vous-même uniquement',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_PRIORITY = [
        'la liberté' => 'la liberté',
        'la sécurité' => 'la sécurité',
        'l\'égalité' => 'l\'égalité',
        'la propriété' => 'la propriété',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_BLAMED_FOR = [
        'trop vous plaindre' => 'trop vous plaindre',
        'de penser qu\'à court terme' => 'de penser qu\'à court terme',
        'manquer d\'éducation' => 'manquer d\'éducation',
        'être idéalistes' => 'être idéalistes',
    ];

    public string $trust = '';
    public string $priority = '';

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
            $this->trust = $data->getValue('trust');
            $this->priority = $data->getValue('priority');
        }
    }

    /**
     * @return array{
     *      trust: string,
     *      priority: string,
     *      blamedFor: string,
     *      notes: string,
     *      chosenQuestion: string,
     *      chosenTrajectorie: string|null,
     *      answer: string
     * }
     * @throws \TypeError
     * @throws \ValueError
     */
    #[\Override]
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

    /**
     * @param array{
     *      trust: string,
     *      priority: string,
     *      blamedFor: string,
     *      notes: string,
     *      chosenQuestion: string,
     *      chosenTrajectorie: string|null,
     *      answer: string
     * } $data
     * @throws \TypeError
     * @throws \ValueError
     */
    #[\Override]
    public function __unserialize(array $data): void
    {
        $this->trust = $data['trust'];
        $this->priority = $data['priority'];
        $this->blamedFor = $data['blamedFor'];
        $this->notes = $data['notes'];
        $this->chosenQuestion = $data['chosenQuestion'];
        $this->answer = $data['answer'];
        $this->chosenTrajectorie = GameRoles::from($data['chosenTrajectorie'] ?? 'null');
    }

    public function getRenderData(): RoleRender
    {
        $renderData = new RoleRender();
        $renderData->addListable('Vous placeriez mieux votre confiance dans', $this->trust);
        $renderData->addListable('Ce qui vous importe le plus, c\'est', $this->priority);
        $renderData->addListable('On vous reproche avant tout de', $this->blamedFor);
        $renderData->setQuestion($this->chosenQuestion, $this->answer, $this->chosenTrajectorie->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    #[\Override]
    public function isReady(): bool
    {
        return '' !== $this->trust && '' !== $this->priority && $this->partialReady();
    }
}
