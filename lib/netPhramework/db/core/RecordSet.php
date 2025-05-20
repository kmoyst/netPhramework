<?php

namespace netPhramework\db\core;
use Countable;
use Iterator;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\mapping\Table;
use netPhramework\db\mapping\Schema;

final class RecordSet implements Iterator, Countable
{
    private ?array $data = null;
    private array $records = [];

	public function __construct(
		private readonly string $name,
		private readonly Schema $schema,
		private readonly Table  $table
	) {}

    public function getName():string
    {
        return $this->name;
    }

	/**
	 * @return string
	 * @throws MappingException
	 */
	public function getIdKey():string
	{
		return $this->schema->getIdKey();
	}

	/**
	 * @return FieldSet
	 * @throws MappingException
	 */
	public function getFieldSet():FieldSet
	{
		return $this->schema->getFieldSet();
	}

	/**
	 * @param string $id
	 * @return Record
	 * @throws RecordNotFound|MappingException
	 */
	public function getRecord(string $id):Record
	{
		$this->ensureRecord($id);
		return $this->records[$id];
	}

	public function newRecord():Record
    {
        return $this->createRecord();
    }

	/**
	 * @return array
	 * @throws MappingException
	 */
	public function getIds():array
	{
		$this->ensureData();
		return array_keys($this->data);
	}

	/**
	 * @return void
	 * @throws MappingException
	 */
	private function ensureData():void
	{
		if ($this->data !== null) return;
		$unindexed = $this->table->select()->getData();
		$this->data = array_column($unindexed, null, $this->getIdKey());
	}

	/**
	 * @param string $id
	 * @return void
	 * @throws MappingException
	 * @throws RecordNotFound
	 */
	private function ensureRecord(string $id):void
	{
		if(array_key_exists($id, $this->records)) return;
		$this->ensureData();
		if(!array_key_exists($id, $this->data))
			throw new RecordNotFound("Record Not Found: $id");
		$this->records[$id] = $this->createRecord($this->data[$id], $id);
	}

	private function createRecord(
		?array $recordData = null, ?string $id = null):Record
	{
		return new Record($this->schema, $this->table, $recordData, $id);
	}

	/**
	 * @return Record
	 * @throws MappingException|RecordNotFound
	 */
    public function current(): Record
    {
		return $this->getRecord($this->key());
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): string
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

	/**
	 * @return void
	 * @throws MappingException
	 */
    public function rewind(): void
    {
        $this->ensureData();
		reset($this->data);
    }

	/**
	 * @return int
	 * @throws MappingException
	 */
	public function count(): int
	{
        $this->ensureData();
		return count($this->data);
	}
}