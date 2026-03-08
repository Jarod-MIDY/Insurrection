<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

class PowerSheet extends AbstractRowSheet implements CharacterSheet
{
    /**
     * @var array<string, string>
     */
    public const array CHOICES_LEGITIMACY = [
        'économique' => 'économique',
        'religieuse' => 'religieuse',
        'républicaine' => 'républicaine',
        'dynastique' => 'dynastique',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_AGENT_TYPE = [
        'des élus' => 'des élus',
        'une caste' => 'une caste',
        'des fonctionnaires' => 'des fonctionnaires',
        'une famille' => 'une famille',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_BLAMED_FOR = [
        'la peur' => 'la peur',
        'l\'omniprésence' => 'l\'omniprésence',
        'l\'esbroufe' => 'l\'esbroufe',
        'le clientélisme' => 'le clientélisme',
    ];

    public string $legitimacy = '';
    public string $importantAgentType = '';

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
            $this->legitimacy = $data->getValue('legitimacy');
            $this->importantAgentType = $data->getValue('importantAgentType');
        }
    }

    /**
     * @return array{
     *      legitimacy: string,
     *      importantAgentType: string,
     *      blamedFor: string,
     *      notes: string,
     *      chosenQuestion: string,
     *      chosenTrajectorie: string|null,
     *      answer: string
     * }
     */
    #[\Override]
    public function __serialize(): array
    {
        return [
            'legitimacy' => $this->legitimacy,
            'importantAgentType' => $this->importantAgentType,
            'blamedFor' => $this->blamedFor,
            'notes' => $this->notes,
            'chosenQuestion' => $this->chosenQuestion,
            'answer' => $this->answer,
            'chosenTrajectorie' => $this->chosenTrajectorie?->value,
        ];
    }

    /**
     * @param array{
     *      legitimacy: string,
     *      importantAgentType: string,
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
        $this->legitimacy = $data['legitimacy'];
        $this->importantAgentType = $data['importantAgentType'];
        $this->blamedFor = $data['blamedFor'];
        $this->notes = $data['notes'];
        $this->chosenQuestion = $data['chosenQuestion'];
        $this->answer = $data['answer'];
        $this->chosenTrajectorie = GameRoles::from($data['chosenTrajectorie'] ?? 'null');
    }

    public function getRenderData(): RoleRender
    {
        $renderData = new RoleRender();
        $renderData->addListable('Votre légitimity est', $this->legitimacy);
        $renderData->addListable('Vos agents importants sont', $this->importantAgentType);
        $renderData->addListable('On vous reproche surtout de gouverner par', $this->blamedFor);
        $renderData->setQuestion($this->chosenQuestion, $this->answer, $this->chosenTrajectorie->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    #[\Override]
    public function isReady(): bool
    {
        return '' !== $this->legitimacy && '' !== $this->importantAgentType && $this->partialReady();
    }
}
