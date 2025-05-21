<?php

namespace netPhramework\db\core;

use netPhramework\db\exceptions\DuplicateEntryException;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\Table;
use netPhramework\db\mapping\Schema;

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
		return $this->schema->getIdKey();
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
			->where($this->getIdKey(), $this->id)
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
            ->withData($this->getCellSetData())
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
            ->withData($this->getCellSetData())
            ->where($this->getIdKey(), $this->id)
            ->confirm();
        return $this;
    }

	/**
	 * Private method used by insert and update methods
	 *
	 * @return array
	 * @throws MappingException
	 */
	private function getCellSetData():array
	{
		$data = [];
		foreach($this->getCellSet() as $cell)
			$data[$cell->getName()] = $cell->getValue();
		return $data;
	}

	/**
	 * Makez sure there's an array of data to build a CellSet
	 * If data wasn't handed in when instantiated, this is a assumed new.
	 * Id would be null as well.
	 *
	 * @return void
	 * @throws MappingException
	 */
	private function ensureData():void
	{
		if($this->data !== null) return;
		$this->data = array_fill_keys($this->getFieldNames(), null);
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