<?php

declare(strict_types=1);

namespace ProjectAutomatization\Utilities;

/**
 * @template TInput
 * @template TOutput
 */
interface PipelineAction
{
    /**
     * @param TInput $input
     *
     * @return TOutput
     */
    public function run($input);
}
