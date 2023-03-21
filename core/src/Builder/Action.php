<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder;

readonly class Action
{
    /**
     * @param string $type
     * @param array<string, string> $inputs
     */
    public function __construct(
        public string $type,
        public array $inputs,
    ) {
    }
}
