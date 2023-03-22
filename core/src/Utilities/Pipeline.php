<?php

declare(strict_types=1);

namespace ProjectAutomatization\Utilities;

/**
 * @template TInput
 * @template TOutput
 */
class Pipeline
{
    /** @var array<PipelineAction<mixed,mixed>> */
    private array $actions;

    /**
     * @template TNewOutput
     *
     * @param PipelineAction<TOutput, TNewOutput> $action
     *
     * @return self<TInput, TNewOutput> $this
     */
    public function addAction(PipelineAction $action): self
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * @param TInput $input
     *
     * @return TOutput
     */
    public function run($input)
    {
        $current = $input;
        foreach ($this->actions as $action) {
            $current = $action->run($input);
        }

        return $current;
    }
}
