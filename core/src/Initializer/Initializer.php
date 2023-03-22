<?php

declare(strict_types=1);

namespace ProjectAutomatization\Initializer;

use ProjectAutomatization\Actions\Action;
use ProjectAutomatization\Builder\Parser\Action as BuiltAction;
use ProjectAutomatization\Runner\Parser\Action as RunnerAction;

interface Initializer
{
    public function createFromBuiltAction(BuiltAction $action): Action;
    public function createFromRunnerAction(RunnerAction $action): Action;
}
