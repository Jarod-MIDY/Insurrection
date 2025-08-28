<?php

namespace App\Records;

/**
 * @phpstan-type LabelValue array{
 *      label: string,
 *      value: string,
 * }
 * @phpstan-type Question array{
 *      question: string,
 *      answer: string,
 *      trajectorie: string,
 * }
 * @phpstan-type TrajectorieQuestion array{
 *      question: string,
 *      answer: string,
 *      role: string,
 * }
 */
class RoleRender
{
    /** @var LabelValue[] */
    public array $listable = [];
    /** @var Question */
    public array $question = [
        'question' => '',
        'answer' => '',
        'trajectorie' => '',
    ];
    /** @var TrajectorieQuestion */
    public array $row_question = [
        'question' => '',
        'answer' => '',
        'role' => '',
    ];
    /** @var TrajectorieQuestion */
    public array $traj_question = [
        'question' => '',
        'answer' => '',
        'role' => '',
    ];
    public string $notes = '';

    public function __construct(
        public ?string $name = null,
        public ?string $features = null,
    ) {
        if (null !== $name) {
            $this->addListable('On t\'appelle', $name);
        }
        if (null !== $features) {
            $this->addListable('Ce qu\'on retient de toi', $features);
        }
    }

    public function addListable(string $label, string $value): void
    {
        $this->listable[] = [
            'label' => $label,
            'value' => $value,
        ];
    }

    public function setQuestion(string $question, string $answer, string $trajectorie): void
    {
        $this->question = [
            'question' => $question,
            'answer' => $answer,
            'trajectorie' => $trajectorie,
        ];
    }

    public function setRowQuestion(string $question, string $answer, string $role): void
    {
        $this->row_question = [
            'question' => $question,
            'answer' => $answer,
            'role' => $role,
        ];
    }

    public function setTrajQuestion(string $question, string $answer, string $role): void
    {
        $this->traj_question = [
            'question' => $question,
            'answer' => $answer,
            'role' => $role,
        ];
    }
}
