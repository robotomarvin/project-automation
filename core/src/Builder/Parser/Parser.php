<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder\Parser;

use Generator;

interface Parser
{
    /**
     * @param mixed $input
     *
     * @return iterable<Action>
     */
    public function parse(mixed $input): iterable;
}
