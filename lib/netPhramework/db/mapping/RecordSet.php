<?php

namespace netPhramework\db\mapping;
use Countable;
use Iterator;
use netPhramework\db\abstraction\Schema;
use netPhramework\db\abstraction\Table;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;

final class RecordSet implements Iterator, Countable
{
    private ?array $data = null;
    private array $records = [];
	private Criteria $criteria;

	public function __construct(
		private readonly string $name,
		private readonly Schema $schema,
		private readonly Table  $table
	)
	{
		$this->criteria = new Criteria();
	}

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
		return $this->schema->getPrimary()->getName();
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

	/**
	 * Creates a new Record
	 * If there are any "EQUAL" conditions, ensures that the Record meets that
	 * Condition. Useful for child records.
	 *
	 * @return Record
	 */
	public function newRecord():Record
    {
		$rowData = [];
		foreach($this->criteria as $condition)
		{
			if($condition->getOperator() !== Operator::EQUAL) continue;
			$rowData[$condition->getField()->getName()]
				= $condition->getValue();

		}
        return $this->createRecord($rowData);
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

	public function addCondition(Condition $condition):RecordSet
	{
		$this->criteria->add($condition);
		return $this;
	}

	public function reset():self
	{
		$this->records 	= [];
		$this->data 	= null;
		$this->criteria = new Criteria();
		return $this;
	}

	/**
	 * @param string $name
	 * @return Field
	 * @throws MappingException
	 * @throws FieldAbsent
	 */
	public function getField(string $name):Field
	{
		return $this->getFieldSet()->getField($name);
	}

	/**
	 * @return void
	 * @throws MappingException
	 */
	private function ensureData():void
	{
		if ($this->data !== null) return;
		$query = $this->table->select();
		foreach($this->criteria as $condition)
			$query->where($condition);
		$unindexed = $query->getData();
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