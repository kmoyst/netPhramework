<?php

namespace netPhramework\data\core;

use netPhramework\data\abstraction\Schema;
use netPhramework\data\abstraction\Table;
use netPhramework\data\exceptions\DuplicateEntryException;
use netPhramework\data\exceptions\FieldAbsent;
use netPhramework\data\exceptions\InvalidValue;
use netPhramework\data\exceptions\MappingException;
use netPhramework\data\mapping\Cell;
use netPhramework\data\mapping\CellSet;
use netPhramework\data\mapping\Condition;
use netPhramework\data\mapping\FieldSet;

final class Record
{
	private CellSet $cellSet;

	/**
	 * This should be wrapped, not extended
	 */
    public function __construct(
		private readonly Schema $schema,
		private readonly Table  $table,
		private ?array          $data = null,
		private ?string         $id = null
    ) {}

	/**
	 * @return FieldSet
	 * @throws MappingException
	 */
	public function getFieldSet():FieldSet
	{
		return $this->schema->getFieldSet();
	}

	/**
	 * @return string
	 * @throws MappingException
	 */
	public function getIdKey():string
	{
		return $this->schema->getPrimary()->getName();
	}

	public function getId(): ?string
	{
		return $this->id;
	}

	public function isNew(): bool
	{
		return $this->id === null;
	}

	/**
	 * @return array
	 * @throws MappingException
	 */
	public function getFieldNames():array
	{
		return $this->getFieldSet()->getNames();
	}

	/**
	 * @return CellSet
	 * @throws MappingException
	 */
	public function getCellSet(): CellSet
	{
		$this->ensureCellSet();
		return $this->cellSet;
	}

	/**
	 * @param string $name
	 * @return Cell
	 * @throws FieldAbsent|MappingException
	 */
	public function getCell(string $name): Cell
	{
		return $this->getCellSet()->getCell($name);
	}

	/**
	 * @param string $name
	 * @param string|null $value
	 * @return $this
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws InvalidValue
	 */
	public function setValue(string $name, ?string $value):self
	{
		$this->getCell($name)->setValue($value);
		return $this;
	}

	/**
	 * @param string $name
	 * @return string|null
	 * @throws FieldAbsent|MappingException
	 */
	public function getValue(string $name):?string
	{
		return $this->getCell($name)->getValue();
	}

	/**
	 * @return bool
	 * @throws MappingException
	 */
	public function drop():bool
	{
		return $this->table
			->delete()
			->where(
				new Condition()
					->setField($this->schema->getPrimary())
					->setValue($this->id))
			->confirm();
	}

	/**
     * @return Record
     * @throws DuplicateEntryException
     * @throws MappingException
     */
	public function save():Record
	{
        return $this->id === null ? $this->insert() : $this->update();
	}

	/**
	 * @return Record
	 * @throws DuplicateEntryException
	 * @throws MappingException
	 */
	public function insert():Record
    {
        $this->id = $this->table
			->insert()
            ->withData($this->getCellSet())
            ->confirm();
        return $this;
    }

	/**
	 * @return Record
	 * @throws DuplicateEntryException
	 * @throws MappingException
	 */
    public function update():Record
    {
        $this->table
            ->update()
            ->withData($this->getCellSet())
            ->where(
				new Condition()
					->setField($this->schema->getPrimary())
					->setValue($this->id)
			)
            ->confirm();
        return $this;
    }

	/**
	 * Make sure there's an array of data to build a CellSet
	 * Fill any field that doesn't already exist in the array with null
	 *
	 * @return void
	 * @throws MappingException
	 */
	private function ensureData():void
	{
		if($this->data === null)
		{
			$this->data = [];
		}
		foreach($this->getFieldSet() as $name => $field)
		{
			if(isset($this->data[$name])) continue;
			else $this->data[$name] = null;
		}
	}

	/**
	 * @return void
	 * @throws MappingException
	 */
	private function ensureCellSet():void
	{
		if(isset($this->cellSet)) return;
		$this->ensureData();
		$this->cellSet = new CellSet($this->getFieldSet(), $this->data);
	}
}