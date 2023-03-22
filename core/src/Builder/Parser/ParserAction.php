<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder\Parser;

use ProjectAutomatization\Utilities\IterableWrapper;
use ProjectAutomatization\Utilities\PipelineAction;

/**
 * @implements PipelineAction<string, IterableWrapper<Action>>
 */

class ParserAction implements PipelineAction
{
    private Parser $parser;

    public function __construct(
        ParserFactory $parserFactory,
    ) {
        $this->parser = $parserFactory->create();
    }

    /**
     * @param string $input
     *
     * @return IterableWrapper<Action>
     */
    public function run($input)
    {
        return new IterableWrapper($this->parser->parse($input));
    }
}
