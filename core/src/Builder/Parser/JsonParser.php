<?php

declare(strict_types=1);

namespace ProjectAutomatization\Builder\Parser;

use Generator;
use JsonException;
use ProjectAutomatization\Builder\Exceptions\ParserException;
use Stringable;

class JsonParser implements Parser
{
    private ActionFactory $actionBuilder;

    public function __construct(ActionFactory $actionBuilder)
    {
        $this->actionBuilder = $actionBuilder;
    }

    /**
     * @param mixed $input
     *
     * @return Generator<Action>
     * @throws ParserException
     */
    public function parse(mixed $input): Generator
    {
        $input = $this->getInputAsString($input);

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

    private function getInputAsString(mixed $input): string
    {
        if (is_resource($input)) {
            $input = stream_get_contents($input);
        }

        if ($input instanceof Stringable) {
            $input = (string) $input;
        }

        if (is_string($input) === FALSE) {
            throw new ParserException('JsonParser accepts only string input');
        }

        return $input;
    }
}
