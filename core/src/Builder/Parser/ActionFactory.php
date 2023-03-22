<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder\Parser;

interface ActionFactory
{
    /**
     * @param array{type: string, inputs: array<string, string>} $input
     *
     * @return Action
     */
    public function create(array $input): Action;
}
