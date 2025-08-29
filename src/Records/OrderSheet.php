<?php

namespace App\Records;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;

/**
 * @phpstan-type OrderArray array{
 *      fearedBecause: string,
 *      accountableTo: string,
 *      blamedFor: string,
 *      notes: string,
 *      chosenQuestion: string,
 *      chosenTrajectorie: string|null,
 *      answer: string
 * }
 */
class OrderSheet extends AbstractRowSheet implements CharacterSheet
{
    /**
     * @var array<string, string>
     */
    public const array CHOICES_FEARED_BECAUSE = [
        'l\'armée' => 'l\'armée',
        'la milice' => 'la milice',
        'l\'ordre mystique' => 'l\'ordre mystique',
        'la police secrète' => 'la police secrète',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_ACCOUNTABLE_TO = [
        'au Pouvoir' => 'au Pouvoir',
        'au Peuple' => 'au Peuple',
        'à personne' => 'à personne',
        'à un dogme' => 'à un dogme',
    ];

    /**
     * @var array<string, string>
     */
    public const array CHOICES_BLAMED_FOR = [
        'violents' => 'violents',
        'intrusifs' => 'intrusifs',
        'corrompus' => 'corrompus',
        'inefficaces' => 'inefficaces',
    ];

    public string $fearedBecause = '';
    public string $accountableTo = '';

    public function __construct(?InformationCollection $data = null)
    {
        if (null !== $data) {
            parent::__construct($data);
            $this->fearedBecause = $data->getValue('fearedBecause');
            $this->accountableTo = $data->getValue('accountableTo');
        }
    }

    /**
     * @param OrderArray $data
     */
    public function __unserialize(array $data): void
    {
        $this->fearedBecause = $data['fearedBecause'];
        $this->accountableTo = $data['accountableTo'];
        $this->blamedFor = $data['blamedFor'];
        $this->notes = $data['notes'];
        $this->chosenQuestion = $data['chosenQuestion'];
        $this->answer = $data['answer'];
        $this->chosenTrajectorie = GameRoles::from($data['chosenTrajectorie'] ?? '');
    }

    /**
     * @return OrderArray
     */
    public function __serialize(): array
    {
        return [
            'fearedBecause' => $this->fearedBecause,
            'accountableTo' => $this->accountableTo,
            'blamedFor' => $this->blamedFor,
            'notes' => $this->notes,
            'chosenQuestion' => $this->chosenQuestion,
            'answer' => $this->answer,
            'chosenTrajectorie' => $this->chosenTrajectorie?->value,
        ];
    }

    public function getRenderData(): RoleRender
    {
        $renderData = new RoleRender();
        $renderData->addListable('On vous craint', $this->fearedBecause);
        $renderData->addListable('On vous accuse', $this->accountableTo);
        $renderData->addListable('On vous reproche surtout de gouverner par', $this->blamedFor);
        $renderData->setQuestion($this->chosenQuestion, $this->answer, $this->chosenTrajectorie->value ?? '');
        $renderData->notes = $this->notes;

        return $renderData;
    }

    public function isReady(): bool
    {
        return '' !== $this->fearedBecause && '' !== $this->accountableTo && $this->partialReady();
    }
}
