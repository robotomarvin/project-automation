<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder;

use Generator;
use JsonException;
use ProjectAutomatization\Builder\Exceptions\ParserException;

class Parser
{
    private ActionFactory $actionBuilder;

    public function __construct(ActionFactory $actionBuilder)
    {
        $this->actionBuilder = $actionBuilder;
    }

    /**
     * @param string $input
     *
     * @return Generator<Action>
     * @throws ParserException
     */
    public function parse(string $input): Generator
    {
        try {
            $input = json_decode($input, TRUE, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ParserException($e->getMessage());
        }
        if (is_iterable($input) === FALSE) {
            throw new ParserException('Input is not iterable JSON');
        }

        foreach ($input as $action) {
            yield $this->buildAction($action);
        }
    }

    /**
     * @param array<mixed> $action
     *
     * @return Action
     */
    private function buildAction(array $action): Action
    {
        if (isset($action['type']) === FALSE || is_string($action['type']) === FALSE) {
            throw new ParserException('Action does not have string type value');
        }

        if (isset($action['inputs']) === TRUE && is_array($action['inputs']) === FALSE) {
            throw new ParserException('Action does not have array inputs value');
        }

        return $this->actionBuilder->create([
            'type' => $action['type'],
            'inputs' => $action['inputs'] ?? [],
        ]);
    }
}
