<?php

namespace App\Records;

class InformationCollection
{
    /**
     * @var array<string, string|null>
     */
    public array $informations = [];

    /**
     * @param array<string, string|null> $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->informations[$key] = $value;
        }
    }

    /**
     * @return array<string, string|null>
     */
    public function __toArray(): array
    {
        return $this->getValues();
    }

    public function addValue(string $key, ?string $value): void
    {
        $this->informations[$key] = $value;
    }

    public function getValue(string $key): string
    {
        if (!key_exists($key, $this->informations)) {
            return '';
        }

        return $this->informations[$key] ?? '';
    }

    /**
     * @return array<string, string|null>
     */
    public function getValues(): array
    {
        return $this->informations;
    }
}
