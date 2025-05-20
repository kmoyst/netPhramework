<?php

namespace netPhramework\db\core;
use Iterator;
use netPhramework\db\exceptions\FieldAbsent;

final class FieldSet implements Iterator
{
    private array $fields = [];

    public function add(Field $field):void
    {
        $this->fields[$field->getName()] = $field;
    }

	/**
	 * @param string $name
	 * @return Field
	 * @throws FieldAbsent
	 */
    public function getField(string $name):Field
    {
		if(!isset($this->fields[$name]))
			throw new FieldAbsent("Field not found: $name");
        return $this->fields[$name];
    }

    public function getNames():array
    {
        return array_keys($this->fields);
    }

    public function current(): Field
    {
        return current($this->fields);
    }

    public function next(): void
    {
        next($this->fields);
    }

    public function key(): string
    {
        return key($this->fields);
    }

    public function valid(): bool
    {
        return key($this->fields) !== null;
    }

    public function rewind(): void
    {
        reset($this->fields);
    }
}