<?php

declare(strict_types=1);

namespace ProjectAutomatization\Initializer;

use Generator;
use ProjectAutomatization\Actions\Action;
use ProjectAutomatization\Builder\Parser\Action as BuiltAction;
use ProjectAutomatization\Utilities\IterableWrapper;
use ProjectAutomatization\Utilities\PipelineAction;

/**
 * @implements PipelineAction<IterableWrapper<BuiltAction>, IterableWrapper<Action>>
 */
class InitializerAction implements PipelineAction
{
    public function __construct(
        private readonly Initializer $initializer,
    ) {
    }

    /**
     * @param IterableWrapper<BuiltAction> $input
     *
     * @return IterableWrapper<Action>
     */
    public function run($input)
    {
        return new IterableWrapper($this->iterate($input));
    }

    /**
     * @param IterableWrapper<BuiltAction> $input
     *
     * @return Generator<Action>
     */
    private function iterate(IterableWrapper $input)
    {
        foreach ($input->get() as $builtAction) {
            yield $this->initializer->createFromBuiltAction($builtAction);
        }
    }
}
