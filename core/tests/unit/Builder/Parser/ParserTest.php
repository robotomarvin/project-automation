<?php

namespace tests\unit\Builder\Parser;

use PHPUnit\Framework\TestCase;
use ProjectAutomatization\Builder\Exceptions\ParserException;
use ProjectAutomatization\Builder\Parser\Action;
use ProjectAutomatization\Builder\Parser\ActionFactory;
use ProjectAutomatization\Builder\Parser\JsonParser;

/**
 * @covers \ProjectAutomatization\Builder\Parser\JsonParser
 * @covers \ProjectAutomatization\Builder\Parser\Action
 */
class ParserTest extends TestCase
{
    public const VALID_JSON = '[
        {
            "type":"entry",
            "inputs": {
                "var1":"componentA"
            }
        }
    ]';

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
        $parser = new JsonParser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('invalid');
        iterator_to_array($out);
    }

    public function testNonStringableInput(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse(0);
        iterator_to_array($out);
    }

    public function testStringableInput(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $input = new class {
            public function __toString(): string
            {
                return ParserTest::VALID_JSON;
            }
        };
        $out = $parser->parse($input);
        $this->assertCount(1, iterator_to_array($out));
    }

    public function testStreamInput(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $stream = fopen('data://text/plain,' . self::VALID_JSON, 'rb');
        $out = $parser->parse($stream);
        $this->assertCount(1, iterator_to_array($out));
    }

    public function testNonIterableJson(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('1');
        iterator_to_array($out);
    }

    public function testInvalidAction(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('[{}]');
        iterator_to_array($out);
    }

    public function testActionWithInvalidInputs(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $this->expectException(ParserException::class);
        $out = $parser->parse('[{"type":"entry", "inputs": "invalid"}]');
        iterator_to_array($out);
    }

    public function testEmpty(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $out = $parser->parse('[]');
        $array = iterator_to_array($out);

        $this->assertEquals([], $array);
    }

    public function testValidAction(): void
    {
        $parser = new JsonParser($this->actionFactory);
        $out = $parser->parse(self::VALID_JSON);
        $array = iterator_to_array($out);

        $this->assertCount(1, $array);
        $this->assertInstanceOf(Action::class, $array[0]);
    }
}
