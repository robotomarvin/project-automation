<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder;

use ProjectAutomatization\Actions\Action;
use ProjectAutomatization\Builder\Parser\ParserAction;
use ProjectAutomatization\Builder\Parser\ParserFactory;
use ProjectAutomatization\Initializer\Initializer;
use ProjectAutomatization\Initializer\InitializerAction;
use ProjectAutomatization\Utilities\Pipeline;

class BuildPipeline
{
    public function __construct(
        private readonly ParserFactory $parserFactory,
        private readonly Initializer $initializer,
    ) {
    }

    /**
     * @param string $input
     *
     * @return iterable<Action>
     */
    public function build(string $input): iterable
    {
        /** @var Pipeline<string, string> $pipeline */
        $pipeline = new Pipeline();

        return $pipeline
            ->addAction(new ParserAction($this->parserFactory))
            ->addAction(new InitializerAction($this->initializer))
            ->run($input)
            ->get();
    }
}
