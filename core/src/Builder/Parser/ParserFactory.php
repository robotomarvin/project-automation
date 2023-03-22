<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder\Parser;

interface ParserFactory
{
    public function create(): Parser;
}
