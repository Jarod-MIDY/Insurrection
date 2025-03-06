<?php

namespace App\Interface;

interface CharacterSheet
{
    /**
     * @return array<string, string>
     */
    public function __serialize(): array;

    /**
     * @param array<string, string> $data
     */
    public function __unserialize(array $data): void;

    public function isReady(): bool;
}
