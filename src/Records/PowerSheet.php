<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

/**
 * @phpstan-type PowerArray array{
 *      legitimacy: string,
 *      importantAgentType: string,
 *      blamedFor: string,
 *      notes: string,
 *      chosenQuestion: string,
 *      chosenTrajectorie: string|null,
 *      answer: string
 * }
 */
class PowerSheet implements CharacterSheet
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
    public string $blamedFor = '';
    public string $notes = '';
    public string $chosenQuestion = '';
    public ?GameRoles $chosenTrajectorie = null;
    public string $answer = '';

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            $this->legitimacy = $data->getValue('legitimacy');
            $this->importantAgentType = $data->getValue('importantAgentType');
            $this->blamedFor = $data->getValue('blamedFor');
            $this->notes = $data->getValue('notes');
            $this->chosenQuestion = $data->getValue('chosenQuestion');
            $this->answer = $data->getValue('answer');
            $this->chosenTrajectorie = GameRoles::from($data->getValue('chosenTrajectorie'));
        }
    }

    /**
     * @return PowerArray
     */
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
     * @param PowerArray $data
     */
    public function __unserialize(array $data): void
    {
        $this->legitimacy = $data['legitimacy'];
        $this->importantAgentType = $data['importantAgentType'];
        $this->blamedFor = $data['blamedFor'];
        $this->notes = $data['notes'];
        $this->chosenQuestion = $data['chosenQuestion'];
        $this->answer = $data['answer'];
        $this->chosenTrajectorie = GameRoles::from($data['chosenTrajectorie'] ?? '');
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

    public function isReady(): bool
    {
        return '' !== $this->legitimacy && '' !== $this->importantAgentType && '' !== $this->blamedFor && '' !== $this->chosenQuestion && null !== $this->chosenTrajectorie;
    }
}
