<?php

use PHPUnit\Framework\TestCase;
use function BenTools\IterableFunctions\iterable_to_array;

final class IterableToArrayTest extends TestCase
{
    public function testIteratorToArray(): void
    {
        $iterator = new ArrayIterator(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], iterable_to_array($iterator));
    }

    public function testIteratorWithoutKeysToArray(): void
    {
        $iterator = new ArrayIterator([1 => 'foo', 2 => 'bar']);
        $this->assertEquals([0 => 'foo', 1 => 'bar'], iterable_to_array($iterator, false));
    }

    public function testArrayToArray(): void
    {
        $array = ['foo', 'bar'];
        $this->assertEquals(['foo', 'bar'], iterable_to_array($array));
    }

    public function testArrayWithoutKeysToArray(): void
    {
        $array = [1 => 'foo', 2 => 'bar'];
        $this->assertEquals([0 => 'foo', 1 => 'bar'], iterable_to_array($array, false));
    }

    public function testScalarToArray(): void
    {
        $scalar = 'foobar';
        $this->assertTrue($this->triggersError($scalar));
    }

    public function testObjectToArray(): void
    {
        $object = new stdClass();
        $this->assertTrue($this->triggersError($object));
    }

    public function testResourceToArray(): void
    {
        $resource = fopen('php://temp', 'rb');
        $this->assertTrue($this->triggersError($resource));
    }

    private function triggersError($input): bool
    {
        $errorOccured = false;

        try {
            iterable_to_array($input);
        } catch (\TypeError $e) {
            $errorOccured = true;
        }

        return $errorOccured;
    }

}