<?php

declare(strict_types=1);

namespace ProjectAutomatization\Utilities;

/**
 * @template T
 */
class IterableWrapper
{
    /**
     * @param iterable<T> $iterable
     */
    public function __construct(private readonly iterable $iterable)
    {
    }

    /**
     * @return iterable<T>
     */
    public function get(): iterable
    {
        return $this->iterable;
    }
}
