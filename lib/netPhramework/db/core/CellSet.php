<?php

namespace netPhramework\db\core;
use Iterator;
use netPhramework\db\exceptions\FieldAbsent;

final class CellSet implements Iterator
{
	private array $cells = [];

    public function __construct(
		private readonly FieldSet $fieldSet,
		private readonly array    $data) {}

    /**
     * @param string $name
     * @return void
     * @throws FieldAbsent
     */
    private function ensureCell(string $name):void
    {
        if(isset($this->cells[$name])) return;
		$field = $this->fieldSet->getField($name);
		$data  = $this->data[$name];
        $this->cells[$name] = new Cell($field, $data);
    }

	public function has(string $name):bool
	{
		return array_key_exists($name, $this->data);
	}

    /**
     * @param string $name
     * @return Cell
     * @throws FieldAbsent
     */
	public function getCell(string $name):Cell
	{
		$this->ensureCell($name);
		return $this->cells[$name];
	}

    /**
     * @return Cell
     * @throws FieldAbsent
     */
    public function current(): Cell
    {
        $this->ensureCell($this->key());
        return $this->cells[$this->key()];
    }

    public function next(): void
    {
        $this->fieldSet->next();
    }

    public function key(): string
    {
        return $this->fieldSet->key();
    }

    public function valid(): bool
    {
        return $this->fieldSet->valid();
    }

    public function rewind(): void
    {
        $this->fieldSet->rewind();
    }
}