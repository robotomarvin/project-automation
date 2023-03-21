<?php

namespace tests\unit\Builder;

use PHPUnit\Framework\TestCase;
use ProjectAutomatization\Builder\Action;
use ProjectAutomatization\Builder\ActionFactory;
use ProjectAutomatization\Builder\Exceptions\ParserException;
use ProjectAutomatization\Builder\Parser;

/**
 * @covers \ProjectAutomatization\Builder\Parser
 * @covers \ProjectAutomatization\Builder\Action
 */
class ParserTest extends TestCase
{
    private ActionFactory $actionFactory;

    protected function setUp(): void
    {
        $this->actionFactory = new class implements ActionFactory {
            public function create(array $input): Action
            {
                return new Action($input['type'], $input['inputs']);
            }
        };
    }

    public function testFailedJson(): void
    {
        $parser = new Parser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('invalid');
        iterator_to_array($out);
    }

    public function testNonIterableJson(): void
    {
        $parser = new Parser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('1');
        iterator_to_array($out);
    }

    public function testInvalidAction(): void
    {
        $parser = new Parser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('[{}]');
        iterator_to_array($out);
    }

    public function testActionWithInvalidInputs(): void
    {
        $parser = new Parser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('[{"type":"entry", "inputs": "invalid"}]');
        iterator_to_array($out);
    }

    public function testEmpty(): void
    {
        $parser = new Parser($this->actionFactory);
        $out = $parser->parse('[]');
        $array = iterator_to_array($out);

        $this->assertEquals([], $array);
    }

    public function testValidAction(): void
    {
        $parser = new Parser($this->actionFactory);
        $out = $parser->parse('[{"type":"entry"}]');
        $array = iterator_to_array($out);

        $this->assertCount(1, $array);
    }
}
