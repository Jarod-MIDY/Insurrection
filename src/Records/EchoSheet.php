<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

/**
 * @phpstan-type EchoArray array{
 *      answer: string,
 *      blamedFor: string,
 *      chosenQuestion: string,
 *      chosenTrajectorie: string|null,
 *      financedBy: string,
 *      importantAgentType: string,
 *      notes: string
 * }
 */
class EchoSheet implements CharacterSheet
{
    public const CHOICES_FINANCED_BY = [
        'le Pouvoir' => 'le Pouvoir',
        'le Peuple' => 'le Peuple',
        'une puissance étrangère' => 'une puissance étrangère',
        'personne, et c’est bien le problème' => 'personne, et c’est bien le problème',
    ];

    public const CHOICES_IMPORTANT_AGENT_TYPE = [
        'des investigateurs' => 'des investigateurs',
        'des animateurs' => 'des animateurs',
        'des éditorialistes' => 'des éditorialistes',
        'des experts' => 'des experts',
    ];

    public const CHOICES_BLAMED_FOR = [
        'aux ordres' => 'aux ordres',
        'déconnectés' => 'déconnectés',
        'populistes' => 'populistes',
        'corporatistes' => 'corporatistes',
    ];

    public string $financedBy = '';
    public string $importantAgentType = '';
    public string $blamedFor = '';
    public string $notes = '';
    public string $chosenQuestion = '';
    public ?GameRoles $chosenTrajectorie = null;
    public string $answer = '';

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            $this->financedBy = $data->getValue('financedBy');
            $this->importantAgentType = $data->getValue('importantAgentType');
            $this->blamedFor = $data->getValue('blamedFor');
            $this->notes = $data->getValue('notes');
            $this->chosenQuestion = $data->getValue('chosenQuestion');
            $this->answer = $data->getValue('answer');
            $this->chosenTrajectorie = GameRoles::from($data->getValue('chosenTrajectorie'));
        }
    }

    /**
     * @return EchoArray
     */
    public function __serialize(): array
    {
        return [
            'financedBy' => $this->financedBy,
            'importantAgentType' => $this->importantAgentType,
            'blamedFor' => $this->blamedFor,
            'notes' => $this->notes,
            'chosenQuestion' => $this->chosenQuestion,
            'answer' => $this->answer,
            'chosenTrajectorie' => $this->chosenTrajectorie?->value,
        ];
    }

    /**
     * @param EchoArray $data
     */
    public function __unserialize(array $data): void
    {
        $this->financedBy = $data['financedBy'];
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
        $renderData->addListable('Vous êtes principalement financés par', $this->financedBy);
        $renderData->addListable('Vos représentants les plus influents sont', $this->importantAgentType);
        $renderData->addListable('On vous reproche principalement d\'abord d\'être', $this->blamedFor);
        $renderData->setQuestion($this->chosenQuestion, $this->answer, $this->chosenTrajectorie->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    public function isReady(): bool
    {
        return '' !== $this->financedBy && '' !== $this->importantAgentType && '' !== $this->blamedFor && '' !== $this->chosenQuestion && null !== $this->chosenTrajectorie;
    }
}
